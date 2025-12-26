<?php

namespace App\Http\Controllers;

use App\Models\CvTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CvTemplateController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $templates = CvTemplate::availableForUser($userId)
            ->orderByDesc('is_system')
            ->orderBy('name')
            ->get();

        return view('cv.templates.index', compact('templates'));
    }

    public function create()
    {
        $base = CvTemplate::where('slug', 'default')->first();
        $template = new CvTemplate([
            'slug' => Str::slug('nueva-plantilla'),
            'html' => $base?->html ?? '<div class="cv-page"><div class="cv-shell"><h1>{{ $fullName }}</h1></div></div>',
            'css' => $base?->css ?? '',
            'options' => $base?->options ?? [
                'primary_color' => '#003366',
                'accent_color' => '#0d3b66',
                'font_family' => "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                'base_font_size' => '11px',
                'heading_font_size' => '13px',
            ],
        ]);

        return view('cv.templates.create', compact('template'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['user_id'] = Auth::id();
        $data['is_system'] = false;

        $template = CvTemplate::create($data);

        return redirect()->route('cv.templates.edit', $template)->with('status', 'Plantilla creada');
    }

    public function show(CvTemplate $template)
    {
        $this->authorizeView($template);
        $renderedHtml = $template->render($this->sampleData());

        return view('cv.templates.show', compact('template', 'renderedHtml'));
    }

    public function edit(CvTemplate $template)
    {
        $this->authorizeEdit($template);

        return view('cv.templates.edit', compact('template'));
    }

    public function update(Request $request, CvTemplate $template)
    {
        $this->authorizeEdit($template);
        $data = $this->validateData($request, $template->id);

        $template->update($data);

        return redirect()->route('cv.templates.edit', $template)->with('status', 'Plantilla actualizada');
    }

    public function destroy(CvTemplate $template)
    {
        $this->authorizeEdit($template);
        $template->delete();

        return redirect()->route('cv.templates.index')->with('status', 'Plantilla eliminada');
    }

    public function preview(Request $request)
    {
        $data = $this->validateData($request, null, true);
        $template = new CvTemplate($data);

        return response()->json([
            'html' => $template->render($this->sampleData()),
        ]);
    }

    private function validateData(Request $request, ?int $templateId = null, bool $skipSlugRequired = false): array
    {
        $slugRule = Rule::unique('cv_templates', 'slug');
        if ($templateId) {
            $slugRule = $slugRule->ignore($templateId);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => array_filter(['required', 'alpha_dash', 'max:50', $slugRule]),
            'description' => ['nullable', 'string', 'max:255'],
            'html' => ['required', 'string'],
            'css' => ['nullable', 'string'],
            'options' => ['nullable', 'array'],
            'options.primary_color' => ['nullable', 'string', 'max:20'],
            'options.accent_color' => ['nullable', 'string', 'max:20'],
            'options.font_family' => ['nullable', 'string', 'max:255'],
            'options.base_font_size' => ['nullable', 'string', 'max:20'],
            'options.heading_font_size' => ['nullable', 'string', 'max:20'],
        ];

        if ($skipSlugRequired) {
            $rules['slug'] = ['nullable', 'alpha_dash', 'max:50'];
            $rules['name'] = ['nullable', 'string', 'max:255'];
        }

        return $request->validate($rules);
    }

    private function authorizeView(CvTemplate $template): void
    {
        abort_unless(
            $template->is_system || $template->user_id === Auth::id(),
            403
        );
    }

    private function authorizeEdit(CvTemplate $template): void
    {
        abort_unless(
            $template->is_system || $template->user_id === Auth::id(),
            403
        );
    }

    private function sampleData(): array
    {
        $faker = fake();

        $experiences = collect([
            [
                'id' => 1,
                'title' => 'Administrador de Servidores',
                'company' => 'UCN',
                'location' => 'Antofagasta',
                'start_date' => now()->subYears(2),
                'end_date' => null,
                'current' => true,
                'description' => "Administracion de servidores para licencias y DNS.\nImplementacion de monitoreo y backup.",
            ],
            [
                'id' => 2,
                'title' => 'Tecnico TI',
                'company' => 'Noa Soto Networks',
                'location' => 'Antofagasta',
                'start_date' => now()->subYears(3),
                'end_date' => now()->subYears(2),
                'current' => false,
                'description' => "Soporte tecnico en sitio y remoto.\nMantenimiento de redes y puntos de acceso.",
            ],
        ])->map(fn ($item) => (object) $item);

        $educations = collect([
            [
                'id' => 1,
                'degree' => 'Ingenieria en Computacion e Informatica',
                'institution' => 'Universidad Catolica del Norte',
                'location' => 'Antofagasta',
                'start_date' => now()->subYears(6),
                'end_date' => null,
                'current' => true,
                'description' => 'Estudios enfocados en infraestructura y desarrollo de software.',
            ],
        ])->map(fn ($item) => (object) $item);

        $certificates = collect([
            [
                'id' => 1,
                'name' => 'Networking Basics',
                'issuer' => 'Cisco',
                'date' => now()->subYear(),
                'credential_id' => null,
                'credential_url' => null,
            ],
            [
                'id' => 2,
                'name' => 'Introduction to Cybersecurity',
                'issuer' => 'Cisco',
                'date' => now()->subMonths(6),
                'credential_id' => null,
                'credential_url' => null,
            ],
        ])->map(fn ($item) => (object) $item);

        $skills = collect([
            ['name' => 'Linux', 'level' => 'Avanzado'],
            ['name' => 'Laravel', 'level' => 'Intermedio'],
            ['name' => 'Redes', 'level' => 'Avanzado'],
        ])->map(fn ($item) => (object) $item);

        $languages = collect([
            ['id' => 1, 'name' => 'Ingles', 'level' => 'B2'],
            ['id' => 2, 'name' => 'Español', 'level' => 'Nativo'],
        ])->map(fn ($item) => (object) $item);

        $profile = (object) [
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'headline' => 'Ingeniero (c) en Computacion e Informatica',
            'summary' => 'Perfil con enfoque en infraestructura, soporte TI y ciberseguridad.',
            'phone' => '+56 9 1234 5678',
            'location' => 'Antofagasta, Chile',
            'website' => 'https://example.com',
            'linkedin' => 'https://linkedin.com/in/usuario',
            'github' => 'https://github.com/usuario',
            'driver_license' => true,
            'driver_license_type' => 'B',
        ];

        $user = (object) [
            'name' => $profile->first_name . ' ' . $profile->last_name,
            'email' => $faker->safeEmail(),
        ];

        $fullName = trim($profile->first_name . ' ' . $profile->last_name);
        $headline = $profile->headline;
        $contactLine = trim(implode(' | ', array_filter([
            $profile->location,
            $user->email,
            $profile->phone,
            $profile->website,
            $profile->linkedin,
            $profile->github,
        ])));
        $summary = $profile->summary;
        $targetRole = null;

        return compact(
            'user',
            'profile',
            'experiences',
            'certificates',
            'educations',
            'skills',
            'languages',
            'fullName',
            'headline',
            'contactLine',
            'summary',
            'targetRole'
        );
    }
}
