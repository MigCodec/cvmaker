<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva educacion</h2>
</x-slot>

<div class="py-6 max-w-2xl mx-auto p-6">
    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Paso 3 - Educacion</p>
    <h1 class="text-2xl font-semibold mb-2">Nueva educacion</h1>
    <p class="text-sm text-gray-600 mb-4">Agrega cada grado o curso por separado. Puedes guardar y seguir sumando al instante.</p>
    <form method="POST" action="{{ route('cv.educations.store') }}" class="space-y-4">
        @csrf
        @include('cv.educations.form')
        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
            <button class="px-4 py-2 bg-white border rounded" type="submit" name="add_another" value="1">Guardar y agregar otra</button>
            <a href="{{ route('cv.educations.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
        </div>
    </form>
</div>
</x-app-layout>
