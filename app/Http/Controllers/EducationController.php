<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    public function index()
    {
        $items = Education::where('user_id', Auth::id())->latest('start_date')->get();
        return view('cv.educations.index', compact('items'));
    }

    public function create()
    {
        return view('cv.educations.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['current'] = $request->boolean('current');
        if ($data['current']) {
            $data['end_date'] = null;
        }
        $data['user_id'] = Auth::id();
        Education::create($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.educations.create' : 'cv.educations.index';
        $status = $request->boolean('add_another')
            ? 'Educacion guardada, agrega otra si lo necesitas'
            : 'Educacion creada';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function edit(Education $education)
    {
        $this->authorizeOwner($education);
        return view('cv.educations.edit', compact('education'));
    }

    public function update(Request $request, Education $education)
    {
        $this->authorizeOwner($education);
        $data = $this->validateData($request);
        $data['current'] = $request->boolean('current');
        if ($data['current']) {
            $data['end_date'] = null;
        }
        $education->update($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.educations.create' : 'cv.educations.index';
        $status = $request->boolean('add_another')
            ? 'Educacion actualizada, agrega otra si lo necesitas'
            : 'Educacion actualizada';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function destroy(Education $education)
    {
        $this->authorizeOwner($education);
        $education->delete();
        return redirect()->route('cv.educations.index')->with('status', 'Educacion eliminada');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'field' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function authorizeOwner(Education $education): void
    {
        abort_unless($education->user_id === Auth::id(), 403);
    }
}
