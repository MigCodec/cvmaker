<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar habilidad</h2>
</x-slot>

<div class="py-6 max-w-2xl mx-auto p-6">
    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Paso 5 - Habilidades</p>
    <h1 class="text-2xl font-semibold mb-2">Editar habilidad</h1>
    <p class="text-sm text-gray-600 mb-4">Ajusta el nivel y el orden sin mezclar datos de otras secciones.</p>
    <form method="POST" action="{{ route('cv.skills.update', $skill) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('cv.skills.form', ['skillModel' => $skill])
        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Actualizar</button>
            <button class="px-4 py-2 bg-white border rounded" type="submit" name="add_another" value="1">Actualizar y agregar otra</button>
            <a href="{{ route('cv.skills.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
        </div>
    </form>
</div>
</x-app-layout>
