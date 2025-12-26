<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo CV</h2>
</x-slot>

<div class="py-6 max-w-5xl mx-auto p-6 space-y-4">
    <div>
        <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Constructor de CV</p>
        <h1 class="text-2xl font-semibold">Crear CV para una postulacion</h1>
        <p class="text-sm text-gray-600">Selecciona solo la experiencia, educacion, certificados y habilidades relevantes.</p>
    </div>

    <form method="POST" action="{{ route('cvs.store') }}" class="space-y-6">
        @csrf
        @include('cv.cvs.form')
        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Guardar CV</button>
            <a href="{{ route('cvs.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
        </div>
    </form>
</div>
</x-app-layout>
