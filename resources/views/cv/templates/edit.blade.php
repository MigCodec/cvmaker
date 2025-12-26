<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar plantilla</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @if (session('status'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow sm:rounded-lg p-6 space-y-4">
            <form id="template-form" method="POST" action="{{ route('cv.templates.update', $template) }}" data-preview-url="{{ route('cv.templates.preview') }}">
                @csrf
                @method('PUT')
                @include('cv.templates.form')
                <div class="flex items-center gap-2 pt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar cambios</button>
                    <a href="{{ route('cv.templates.index') }}" class="px-4 py-2 border rounded">Volver</a>
                    <a href="{{ route('cv.templates.show', $template) }}" class="px-4 py-2 border rounded">Ver preview</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
