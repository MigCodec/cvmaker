<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar CV</h2>
</x-slot>

<div class="py-6 max-w-5xl mx-auto p-6 space-y-4">
    <div>
        <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Constructor de CV</p>
        <h1 class="text-2xl font-semibold">Editar CV: {{ $cv->name }}</h1>
        <p class="text-sm text-gray-600">Combina y ajusta la informacion para esta postulacion.</p>
    </div>

    <form method="POST" action="{{ route('cvs.update', $cv) }}" class="space-y-6">
        @csrf
        @method('PUT')
        @include('cv.cvs.form')
        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Actualizar CV</button>
            <a href="{{ route('cv.preview', $cv) }}" class="px-4 py-2 border rounded">Ver previsualizacion</a>
            <a href="{{ route('cvs.index') }}" class="px-4 py-2 border rounded">Volver</a>
        </div>
    </form>
</div>
</x-app-layout>
