<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Experiencia</h2>
</x-slot>

<div class="py-6 max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Paso 2 - Experiencia</p>
            <h1 class="text-2xl font-semibold">Experiencia laboral</h1>
            <p class="text-sm text-gray-600">Agrega cada puesto por separado para mantener los datos organizados.</p>
        </div>
        <a href="{{ route('cv.experiences.create') }}" class="px-3 py-2 border rounded bg-white hover:bg-gray-50">Agregar</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <div class="space-y-3">
        @forelse ($items as $exp)
            <div class="border rounded p-4">
                <div class="flex justify-between gap-3 flex-wrap">
                    <div>
                        <div class="font-medium">{{ $exp->title }} @if($exp->company) - {{ $exp->company }} @endif</div>
                        <div class="text-sm text-gray-600">
                            {{ optional($exp->start_date)->format('M Y') }} - {{ $exp->current ? 'Actualidad' : optional($exp->end_date)->format('M Y') }}
                        </div>
                        @if($exp->location)
                            <div class="text-sm text-gray-600">{{ $exp->location }}</div>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a class="px-3 py-1 border rounded" href="{{ route('cv.experiences.edit', $exp) }}">Editar</a>
                        <form method="POST" action="{{ route('cv.experiences.destroy', $exp) }}" onsubmit="return confirm('Eliminar este registro?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 border rounded text-red-600">Eliminar</button>
                        </form>
                    </div>
                </div>
                @if($exp->description)
                    <p class="mt-2 text-sm">{{ $exp->description }}</p>
                @endif
            </div>
        @empty
            <p class="text-gray-600">Aun no has agregado experiencia.</p>
        @endforelse
    </div>

    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('cv.profile.edit') }}" class="text-blue-600">Volver al perfil</a>
        <a href="{{ route('cv.educations.index') }}" class="text-blue-600">Continuar con Educacion</a>
    </div>
</div>
</x-app-layout>
