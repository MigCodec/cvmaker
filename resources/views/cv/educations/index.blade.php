<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Educacion</h2>
</x-slot>

<div class="py-6 max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Paso 3 - Educacion</p>
            <h1 class="text-2xl font-semibold">Formacion academica</h1>
            <p class="text-sm text-gray-600">Divide tus estudios por niveles o titulos individuales.</p>
        </div>
        <a href="{{ route('cv.educations.create') }}" class="px-3 py-2 border rounded bg-white hover:bg-gray-50">Agregar</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <div class="space-y-3">
        @forelse ($items as $edu)
            <div class="border rounded p-4">
                <div class="flex justify-between gap-3 flex-wrap">
                    <div>
                        <div class="font-medium">{{ $edu->degree }} - {{ $edu->institution }}</div>
                        <div class="text-sm text-gray-600">
                            {{ optional($edu->start_date)->format('M Y') }} - {{ $edu->current ? 'Actualidad' : optional($edu->end_date)->format('M Y') }}
                        </div>
                        @if($edu->location)
                            <div class="text-sm text-gray-600">{{ $edu->location }}</div>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a class="px-3 py-1 border rounded" href="{{ route('cv.educations.edit', $edu) }}">Editar</a>
                        <form method="POST" action="{{ route('cv.educations.destroy', $edu) }}" onsubmit="return confirm('Eliminar este registro?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 border rounded text-red-600">Eliminar</button>
                        </form>
                    </div>
                </div>
                @if($edu->description)
                    <p class="mt-2 text-sm">{{ $edu->description }}</p>
                @endif
            </div>
        @empty
            <p class="text-gray-600">Aun no has agregado educacion.</p>
        @endforelse
    </div>

    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('cv.experiences.index') }}" class="text-blue-600">Volver a Experiencia</a>
        <a href="{{ route('cv.certificates.index') }}" class="text-blue-600">Continuar con Certificados</a>
    </div>
</div>
</x-app-layout>
