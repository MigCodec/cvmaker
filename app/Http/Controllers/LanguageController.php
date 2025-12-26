<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function index()
    {
        $items = Language::where('user_id', Auth::id())->orderBy('order')->get();
        return view('cv.languages.index', compact('items'));
    }

    public function create()
    {
        return view('cv.languages.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['order'] = $data['order'] ?? 0;
        $data['user_id'] = Auth::id();
        Language::create($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.languages.create' : 'cv.languages.index';
        $status = $request->boolean('add_another')
            ? 'Idioma guardado, agrega otro si lo necesitas'
            : 'Idioma creado';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function edit(Language $language)
    {
        $this->authorizeOwner($language);
        return view('cv.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $this->authorizeOwner($language);
        $data = $this->validateData($request);
        $data['order'] = $data['order'] ?? 0;
        $language->update($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.languages.create' : 'cv.languages.index';
        $status = $request->boolean('add_another')
            ? 'Idioma actualizado, agrega otro si lo necesitas'
            : 'Idioma actualizado';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function destroy(Language $language)
    {
        $this->authorizeOwner($language);
        $language->delete();
        return redirect()->route('cv.languages.index')->with('status', 'Idioma eliminado');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function authorizeOwner(Language $language): void
    {
        abort_unless($language->user_id === Auth::id(), 403);
    }
}
