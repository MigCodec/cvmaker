<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Idiomas</h2>
</x-slot>

<div class="py-6 max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Paso 6 - Idiomas</p>
            <h1 class="text-2xl font-semibold">Idiomas</h1>
            <p class="text-sm text-gray-600">Agrega los idiomas y su nivel.</p>
        </div>
        <a href="{{ route('cv.languages.create') }}" class="px-3 py-2 border rounded bg-white hover:bg-gray-50">Agregar</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <div class="space-y-3">
        @forelse ($items as $lang)
            <div class="border rounded p-4 flex items-center justify-between">
                <div>
                    <div class="font-medium">{{ $lang->name }}</div>
                    @if($lang->level)
                        <div class="text-sm text-gray-600">{{ $lang->level }}</div>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-1 border rounded" href="{{ route('cv.languages.edit', $lang) }}">Editar</a>
                    <form method="POST" action="{{ route('cv.languages.destroy', $lang) }}" onsubmit="return confirm('Eliminar este registro?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 border rounded text-red-600">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Aun no has agregado idiomas.</p>
        @endforelse
    </div>

    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('cv.skills.index') }}" class="text-blue-600">Volver a habilidades</a>
        <a href="{{ route('cv.preview') }}" class="text-blue-600">Ver previsualizacion</a>
    </div>
</div>
</x-app-layout>
