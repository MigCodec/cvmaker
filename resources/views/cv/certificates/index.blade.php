<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Certificados</h2>
</x-slot>

<div class="py-6 max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide">Paso 4 - Certificados</p>
            <h1 class="text-2xl font-semibold">Certificados</h1>
            <p class="text-sm text-gray-600">Cada certificacion se guarda aparte para mantener el CV segmentado.</p>
        </div>
        <a href="{{ route('cv.certificates.create') }}" class="px-3 py-2 border rounded bg-white hover:bg-gray-50">Agregar</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <div class="space-y-3">
        @forelse ($items as $cert)
            <div class="border rounded p-4 flex justify-between gap-3 flex-wrap">
                <div>
                    <div class="font-medium">{{ $cert->name }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $cert->issuer }}
                        @if($cert->date)
                            - {{ $cert->date->format('M Y') }}
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-1 border rounded" href="{{ route('cv.certificates.edit', $cert) }}">Editar</a>
                    <form method="POST" action="{{ route('cv.certificates.destroy', $cert) }}" onsubmit="return confirm('Eliminar este registro?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1 border rounded text-red-600">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Aun no has agregado certificados.</p>
        @endforelse
    </div>

    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('cv.educations.index') }}" class="text-blue-600">Volver a Educacion</a>
        <a href="{{ route('cv.skills.index') }}" class="text-blue-600">Continuar con Habilidades</a>
    </div>
</div>
</x-app-layout>
