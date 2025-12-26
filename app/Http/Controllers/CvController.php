<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Cv;
use App\Models\CvTemplate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CvController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cvs = $user->cvs()->withCount(['experiences', 'educations', 'certificates', 'skills'])->latest()->get();

        return view('cv.cvs.index', compact('cvs'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('cv.cvs.create', [
            'cv' => new Cv(['template' => $user->profile->template ?? 'default']),
            'experiences' => $user->experiences()->orderByDesc('start_date')->get(),
            'educations' => $user->educations()->orderByDesc('start_date')->get(),
            'certificates' => $user->certificates()->orderByDesc('date')->get(),
            'skills' => $user->skills()->get(),
            'languages' => $user->languages()->get(),
            'templates' => CvTemplate::availableForUser($user->id)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $user = Auth::user();

        $cv = Cv::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'target_role' => $data['target_role'] ?? null,
            'summary' => $data['summary'] ?? null,
            'template' => $data['template'] ?? 'default',
        ]);

        $this->syncRelations($cv, $data);

        return redirect()->route('cvs.index')->with('status', 'CV creado. Ahora puedes previsualizarlo.');
    }

    public function edit(Cv $cv)
    {
        $this->authorizeOwner($cv);
        $user = Auth::user();

        return view('cv.cvs.edit', [
            'cv' => $cv->load(['experiences', 'educations', 'certificates', 'skills']),
            'experiences' => $user->experiences()->orderByDesc('start_date')->get(),
            'educations' => $user->educations()->orderByDesc('start_date')->get(),
            'certificates' => $user->certificates()->orderByDesc('date')->get(),
            'skills' => $user->skills()->get(),
            'languages' => $user->languages()->get(),
            'templates' => CvTemplate::availableForUser($user->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Cv $cv)
    {
        $this->authorizeOwner($cv);
        $data = $this->validateData($request);

        $cv->update([
            'name' => $data['name'],
            'target_role' => $data['target_role'] ?? null,
            'summary' => $data['summary'] ?? null,
            'template' => $data['template'] ?? 'default',
        ]);

        $this->syncRelations($cv, $data);

        return redirect()->route('cvs.index')->with('status', 'CV actualizado.');
    }

    public function destroy(Cv $cv)
    {
        $this->authorizeOwner($cv);
        $cv->delete();

        return redirect()->route('cvs.index')->with('status', 'CV eliminado.');
    }

    public function preview(Request $request, ?Cv $cv = null)
    {
        $user = Auth::user();
        if ($cv) {
            $this->authorizeOwner($cv);
        }

        $profile = $user->profile;
        $templateSlug = $request->input('template') ?? ($cv->template ?? $profile->template ?? 'default');

        if ($cv) {
            $experiences = $cv->experiences()->orderByDesc('start_date')->get();
            $certificates = $cv->certificates()->orderByDesc('date')->get();
            $educations = $cv->educations()->orderByDesc('start_date')->get();
            $skills = $cv->skills()->get();
            $languages = $cv->languages()->get();
        } else {
            $experiences = $user->experiences()->orderByDesc('start_date')->get();
            $certificates = $user->certificates()->orderByDesc('date')->get();
            $educations = $user->educations()->orderByDesc('start_date')->get();
            $skills = $user->skills()->get();
            $languages = $user->languages()->get();
        }

        $allExperiences = $experiences;
        $allCertificates = $certificates;
        $allSkills = $skills;
        $allLanguages = $languages;

        $selectedExperiences = collect($request->input('experiences', $experiences->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();
        $selectedCertificates = collect($request->input('certificates', $certificates->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();
        $selectedSkills = collect($request->input('skills', $skills->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();
        $selectedLanguages = collect($request->input('languages', $languages->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $experienceOrder = collect($request->input('experience_order', []))
            ->mapWithKeys(fn ($order, $id) => [(int) $id => (int) $order]);
        $skillsOrder = collect($request->input('skills_order', []))
            ->mapWithKeys(fn ($order, $id) => [(int) $id => (int) $order]);
        $languagesOrder = collect($request->input('languages_order', []))
            ->mapWithKeys(fn ($order, $id) => [(int) $id => (int) $order]);

        $experiences = $experiences
            ->whereIn('id', $selectedExperiences)
            ->sortBy(function ($exp, $key) use ($experienceOrder) {
                return $experienceOrder[$exp->id] ?? ($key + 1000);
            })
            ->values();
        $certificates = $certificates->whereIn('id', $selectedCertificates);
        $skills = $skills
            ->whereIn('id', $selectedSkills)
            ->sortBy(function ($skill, $key) use ($skillsOrder) {
                return $skillsOrder[$skill->id] ?? ($key + 1000);
            })
            ->values();
        $languages = $languages
            ->whereIn('id', $selectedLanguages)
            ->sortBy(function ($lang, $key) use ($languagesOrder) {
                return $languagesOrder[$lang->id] ?? ($key + 1000);
            })
            ->values();

        $summary = $cv?->summary ?? $profile?->summary;
        $targetRole = $cv?->target_role;

        $templates = CvTemplate::availableForUser($user->id)->orderBy('name')->get();
        $template = $templates->firstWhere('slug', $templateSlug) ?? CvTemplate::where('slug', $templateSlug)->first();
        if ($template) {
            $renderedHtml = $template->render($this->buildTemplateData(
                $user,
                $profile,
                $experiences,
                $certificates,
                $educations,
                $skills,
                $languages,
                $summary,
                $targetRole
            ));

            return view('cv.preview.viewer', compact(
                'user',
                'profile',
                'experiences',
                'certificates',
                'allExperiences',
                'allCertificates',
                'allSkills',
                'allLanguages',
                'educations',
                'skills',
                'languages',
                'cv',
                'summary',
                'targetRole',
                'renderedHtml',
                'selectedExperiences',
                'selectedCertificates',
                'selectedSkills',
                'selectedLanguages',
                'experienceOrder',
                'skillsOrder',
                'languagesOrder',
                'template',
                'templates'
            ));
        }

        return view("cv.preview.$templateSlug", compact(
            'user',
            'profile',
            'experiences',
            'certificates',
            'educations',
            'skills',
            'cv',
            'summary',
            'targetRole'
        ));
    }

    public function pdf(Request $request, ?Cv $cv = null)
    {
        $user = Auth::user();
        if ($cv) {
            $this->authorizeOwner($cv);
        }

        $profile = $user->profile;
        $templateSlug = $request->input('template') ?? ($cv->template ?? $profile->template ?? 'default');

        if ($cv) {
            $experiences = $cv->experiences()->orderByDesc('start_date')->get();
            $certificates = $cv->certificates()->orderByDesc('date')->get();
            $educations = $cv->educations()->orderByDesc('start_date')->get();
            $skills = $cv->skills()->get();
            $languages = $cv->languages()->get();
        } else {
            $experiences = $user->experiences()->orderByDesc('start_date')->get();
            $certificates = $user->certificates()->orderByDesc('date')->get();
            $educations = $user->educations()->orderByDesc('start_date')->get();
            $skills = $user->skills()->get();
            $languages = $user->languages()->get();
        }

        $selectedExperiences = collect($request->input('experiences', $experiences->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();
        $selectedCertificates = collect($request->input('certificates', $certificates->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();
        $selectedSkills = collect($request->input('skills', $skills->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();
        $selectedLanguages = collect($request->input('languages', $languages->pluck('id')->all()))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $experienceOrder = collect($request->input('experience_order', []))
            ->mapWithKeys(fn ($order, $id) => [(int) $id => (int) $order]);
        $skillsOrder = collect($request->input('skills_order', []))
            ->mapWithKeys(fn ($order, $id) => [(int) $id => (int) $order]);
        $languagesOrder = collect($request->input('languages_order', []))
            ->mapWithKeys(fn ($order, $id) => [(int) $id => (int) $order]);

        $experiences = $experiences
            ->whereIn('id', $selectedExperiences)
            ->sortBy(function ($exp, $key) use ($experienceOrder) {
                return $experienceOrder[$exp->id] ?? ($key + 1000);
            })
            ->values();
        $certificates = $certificates->whereIn('id', $selectedCertificates);
        $skills = $skills
            ->whereIn('id', $selectedSkills)
            ->sortBy(function ($skill, $key) use ($skillsOrder) {
                return $skillsOrder[$skill->id] ?? ($key + 1000);
            })
            ->values();
        $languages = $languages
            ->whereIn('id', $selectedLanguages)
            ->sortBy(function ($lang, $key) use ($languagesOrder) {
                return $languagesOrder[$lang->id] ?? ($key + 1000);
            })
            ->values();

        $summary = $cv?->summary ?? $profile?->summary;
        $targetRole = $cv?->target_role;

        $template = CvTemplate::availableForUser($user->id)->firstWhere('slug', $templateSlug);
        if ($template) {
            $renderedHtml = $template->render($this->buildTemplateData(
                $user,
                $profile,
                $experiences,
                $certificates,
                $educations,
                $skills,
                $languages,
                $summary,
                $targetRole
            ));

            $pdf = PDF::loadHTML($renderedHtml)->setPaper('letter');
        } else {
            $pdf = PDF::loadView("cv.preview.$templateSlug", compact(
                'user',
                'profile',
                'experiences',
                'certificates',
                'educations',
                'skills',
                'cv',
                'summary',
                'targetRole'
            ))->setPaper('letter');
        }

        return $pdf->download(($cv?->name ? $cv->name . '-' : '') . 'cv.pdf');
    }

    private function validateData(Request $request): array
    {
        $userId = Auth::id();

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_role' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'template' => [
                'nullable',
                'string',
                'max:50',
                Rule::exists('cv_templates', 'slug')->where(function ($query) use ($userId) {
                    $query->where('is_system', true)->orWhere('user_id', $userId);
                }),
            ],
            'experiences' => ['nullable', 'array'],
            'experiences.*' => ['integer', Rule::exists('experiences', 'id')->where('user_id', $userId)],
            'educations' => ['nullable', 'array'],
            'educations.*' => ['integer', Rule::exists('education', 'id')->where('user_id', $userId)],
            'certificates' => ['nullable', 'array'],
            'certificates.*' => ['integer', Rule::exists('certificates', 'id')->where('user_id', $userId)],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['integer', Rule::exists('skills', 'id')->where('user_id', $userId)],
            'languages' => ['nullable', 'array'],
            'languages.*' => ['integer', Rule::exists('languages', 'id')->where('user_id', $userId)],
        ]);
    }

    private function syncRelations(Cv $cv, array $data): void
    {
        $cv->experiences()->sync($data['experiences'] ?? []);
        $cv->educations()->sync($data['educations'] ?? []);
        $cv->certificates()->sync($data['certificates'] ?? []);
        $cv->skills()->sync($data['skills'] ?? []);
        $cv->languages()->sync($data['languages'] ?? []);
    }

    private function authorizeOwner(Cv $cv): void
    {
        abort_unless($cv->user_id === Auth::id(), 403);
    }

    private function buildTemplateData(
        $user,
        $profile,
        $experiences,
        $certificates,
        $educations,
        $skills,
        $languages,
        ?string $summary,
        ?string $targetRole
    ): array {
        $fullName = trim(($profile?->first_name ?? $user->name) . ' ' . ($profile?->last_name ?? ''));
        $headline = $targetRole ?? $profile?->headline;
        $contactLine = trim(implode(' | ', array_filter([
            $profile?->location,
            $user->email,
            $profile?->phone,
            $profile?->website,
            $profile?->linkedin,
            $profile?->github,
        ])));

        return compact(
            'user',
            'profile',
            'experiences',
            'certificates',
            'educations',
            'skills',
            'languages',
            'summary',
            'targetRole',
            'fullName',
            'headline',
            'contactLine'
        );
    }
}
