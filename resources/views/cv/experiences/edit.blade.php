<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar experiencia</h2>
</x-slot>

<div class="py-6 max-w-2xl mx-auto p-6">
    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Paso 2 - Experiencia</p>
    <h1 class="text-2xl font-semibold mb-2">Editar experiencia</h1>
    <p class="text-sm text-gray-600 mb-4">Mantiene cada rol separado para que el CV se arme por secciones.</p>
    <form method="POST" action="{{ route('cv.experiences.update', $experience) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('cv.experiences.form', ['exp' => $experience])
        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded" type="submit">Actualizar</button>
            <button class="px-4 py-2 bg-white border rounded" type="submit" name="add_another" value="1">Actualizar y agregar otra</button>
            <a href="{{ route('cv.experiences.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
        </div>
    </form>
</div>
</x-app-layout>
