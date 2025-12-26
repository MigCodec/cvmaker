<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function index()
    {
        $items = Skill::where('user_id', Auth::id())->orderBy('order')->get();
        return view('cv.skills.index', compact('items'));
    }

    public function create()
    {
        return view('cv.skills.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['user_id'] = Auth::id();
        Skill::create($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.skills.create' : 'cv.skills.index';
        $status = $request->boolean('add_another')
            ? 'Habilidad guardada, agrega otra si lo necesitas'
            : 'Habilidad creada';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function edit(Skill $skill)
    {
        $this->authorizeOwner($skill);
        return view('cv.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $this->authorizeOwner($skill);
        $data = $this->validateData($request);
        $skill->update($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.skills.create' : 'cv.skills.index';
        $status = $request->boolean('add_another')
            ? 'Habilidad actualizada, agrega otra si lo necesitas'
            : 'Habilidad actualizada';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function destroy(Skill $skill)
    {
        $this->authorizeOwner($skill);
        $skill->delete();
        return redirect()->route('cv.skills.index')->with('status', 'Habilidad eliminada');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function authorizeOwner(Skill $skill): void
    {
        abort_unless($skill->user_id === Auth::id(), 403);
    }
}
