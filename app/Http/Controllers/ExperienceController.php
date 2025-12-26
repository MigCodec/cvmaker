<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function index()
    {
        $items = Experience::where('user_id', Auth::id())->latest('start_date')->get();
        return view('cv.experiences.index', compact('items'));
    }

    public function create()
    {
        return view('cv.experiences.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['current'] = $request->boolean('current');
        if ($data['current']) {
            $data['end_date'] = null;
        }
        $data['user_id'] = Auth::id();
        Experience::create($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.experiences.create' : 'cv.experiences.index';
        $status = $request->boolean('add_another')
            ? 'Experiencia guardada, agrega otra si lo necesitas'
            : 'Experiencia creada';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function edit(Experience $experience)
    {
        $this->authorizeOwner($experience);
        return view('cv.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience)
    {
        $this->authorizeOwner($experience);
        $data = $this->validateData($request);
        $data['current'] = $request->boolean('current');
        if ($data['current']) {
            $data['end_date'] = null;
        }
        $experience->update($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.experiences.create' : 'cv.experiences.index';
        $status = $request->boolean('add_another')
            ? 'Experiencia actualizada, agrega otra si lo necesitas'
            : 'Experiencia actualizada';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function destroy(Experience $experience)
    {
        $this->authorizeOwner($experience);
        $experience->delete();
        return redirect()->route('cv.experiences.index')->with('status', 'Experiencia eliminada');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function authorizeOwner(Experience $experience): void
    {
        abort_unless($experience->user_id === Auth::id(), 403);
    }
}
