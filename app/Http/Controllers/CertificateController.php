<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $items = Certificate::where('user_id', Auth::id())->latest('date')->get();
        return view('cv.certificates.index', compact('items'));
    }

    public function create()
    {
        return view('cv.certificates.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['user_id'] = Auth::id();
        Certificate::create($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.certificates.create' : 'cv.certificates.index';
        $status = $request->boolean('add_another')
            ? 'Certificado guardado, agrega otro si lo necesitas'
            : 'Certificado creado';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function edit(Certificate $certificate)
    {
        $this->authorizeOwner($certificate);
        return view('cv.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $this->authorizeOwner($certificate);
        $data = $this->validateData($request);
        $certificate->update($data);

        $nextRoute = $request->boolean('add_another') ? 'cv.certificates.create' : 'cv.certificates.index';
        $status = $request->boolean('add_another')
            ? 'Certificado actualizado, agrega otro si lo necesitas'
            : 'Certificado actualizado';

        return redirect()->route($nextRoute)->with('status', $status);
    }

    public function destroy(Certificate $certificate)
    {
        $this->authorizeOwner($certificate);
        $certificate->delete();
        return redirect()->route('cv.certificates.index')->with('status', 'Certificado eliminado');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'credential_id' => ['nullable', 'string', 'max:255'],
            'credential_url' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function authorizeOwner(Certificate $certificate): void
    {
        abort_unless($certificate->user_id === Auth::id(), 403);
    }
}
