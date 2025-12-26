<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Preview plantilla</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">{{ $template->name }}</h3>
                <p class="text-sm text-gray-600">{{ $template->description }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('cv.templates.edit', $template) }}" class="px-4 py-2 border rounded">Editar</a>
                <a href="{{ route('cv.templates.index') }}" class="px-4 py-2 border rounded">Volver</a>
            </div>
        </div>

        <div class="bg-white shadow border border-gray-200 rounded-xl overflow-hidden">
            {!! $renderedHtml !!}
        </div>
    </div>
</x-app-layout>
