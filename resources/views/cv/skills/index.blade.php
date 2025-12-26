<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Habilidades</h2>
</x-slot>

<div class="py-6 max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Paso 5 - Habilidades</p>
            <h1 class="text-2xl font-semibold">Habilidades</h1>
            <p class="text-sm text-gray-600">Ordena tus skills de forma independiente para mostrarlas mejor en el CV.</p>
        </div>
        <a href="{{ route('cv.skills.create') }}" class="px-3 py-2 border rounded bg-white hover:bg-gray-50">Agregar</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <div class="space-y-3">
        @forelse ($items as $skill)
            <div class="border rounded p-4 flex justify-between gap-3 flex-wrap">
                <div>
                    <div class="font-medium">{{ $skill->name }}</div>
                    <div class="text-sm text-gray-600">
                        Nivel: {{ $skill->level ?: 'No indicado' }}
                        @if(!is_null($skill->order))
                            - Orden: {{ $skill->order }}
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-1 border rounded" href="{{ route('cv.skills.edit', $skill) }}">Editar</a>
                    <form method="POST" action="{{ route('cv.skills.destroy', $skill) }}" onsubmit="return confirm('Eliminar este registro?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 border rounded text-red-600">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Aun no has agregado habilidades.</p>
        @endforelse
    </div>

    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('cv.certificates.index') }}" class="text-blue-600">Volver a Certificados</a>
        <a href="{{ route('cv.preview') }}" class="text-blue-600">Ver previsualizacion</a>
    </div>
</div>
</x-app-layout>
