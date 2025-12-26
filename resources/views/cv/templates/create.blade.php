<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva plantilla</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="bg-white shadow sm:rounded-lg p-6 space-y-4">
            <form id="template-form" method="POST" action="{{ route('cv.templates.store') }}" data-preview-url="{{ route('cv.templates.preview') }}">
                @csrf
                @include('cv.templates.form')
                <div class="flex items-center gap-2 pt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
                    <a href="{{ route('cv.templates.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
