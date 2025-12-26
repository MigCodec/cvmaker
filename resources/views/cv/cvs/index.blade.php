<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">CVs Dinamicos</h2>
</x-slot>

<div class="py-6 max-w-6xl mx-auto p-6 space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Constructor de CV</p>
            <h1 class="text-2xl font-semibold">Tus combinaciones de CV</h1>
            <p class="text-sm text-gray-600">Cada CV combina items que ya cargaste: experiencia, educacion, certificados y habilidades.</p>
        </div>
        <a href="{{ route('cvs.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Nuevo CV</a>
    </div>

    @if (session('status'))
        <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($cvs as $cv)
            <div class="border rounded-lg bg-white shadow-sm p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $cv->name }}</h3>
                        @if($cv->target_role)
                            <p class="text-sm text-gray-600">Rol: {{ $cv->target_role }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('cvs.destroy', $cv) }}" onsubmit="return confirm('Eliminar este CV?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 text-sm">Eliminar</button>
                    </form>
                </div>
                <div class="text-xs text-gray-600">Exp: {{ $cv->experiences_count }} | Edu: {{ $cv->educations_count }} | Cert: {{ $cv->certificates_count }} | Skills: {{ $cv->skills_count }}</div>
                <div class="flex gap-2 flex-wrap pt-1">
                    <a href="{{ route('cv.preview', $cv) }}" class="px-3 py-1 border rounded text-sm">Preview</a>
                    <a href="{{ route('cv.pdf', $cv) }}" class="px-3 py-1 border rounded text-sm">PDF</a>
                    <a href="{{ route('cvs.edit', $cv) }}" class="px-3 py-1 border rounded text-sm">Editar</a>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Aun no has creado CVs. Crea uno nuevo para combinar tus datos.</p>
        @endforelse
    </div>
</div>
</x-app-layout>
