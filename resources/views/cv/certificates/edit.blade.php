<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar certificado</h2>
</x-slot>

<div class="py-6 max-w-2xl mx-auto p-6">
    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Paso 4 - Certificados</p>
    <h1 class="text-2xl font-semibold mb-2">Editar certificado</h1>
    <p class="text-sm text-gray-600 mb-4">Ajusta la informacion y sigue agregando mas items cuando lo necesites.</p>
    <form method="POST" action="{{ route('cv.certificates.update', $certificate) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('cv.certificates.form', ['cert' => $certificate])
        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Actualizar</button>
            <button class="px-4 py-2 bg-white border rounded" type="submit" name="add_another" value="1">Actualizar y agregar otro</button>
            <a href="{{ route('cv.certificates.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
        </div>
    </form>
</div>
</x-app-layout>
