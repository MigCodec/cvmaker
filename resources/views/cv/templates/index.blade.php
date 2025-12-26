<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Plantillas de CV</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @if (session('status'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Administra tus plantillas y prueba nuevos estilos.</p>
            </div>
            <a href="{{ route('cv.templates.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Nueva plantilla</a>
        </div>

        <div class="bg-white shadow sm:rounded-lg">
            <div class="divide-y">
                @forelse($templates as $template)
                    <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-semibold">{{ $template->name }}</h3>
                                @if($template->is_system)
                                    <span class="text-xs uppercase tracking-wide bg-gray-100 text-gray-600 px-2 py-1 rounded">Sistema</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">{{ $template->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">Slug: {{ $template->slug }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('cv.templates.show', $template) }}" class="px-3 py-1 border rounded text-sm">Ver</a>
                            <a href="{{ route('cv.templates.edit', $template) }}" class="px-3 py-1 border rounded text-sm">Editar</a>
                            <form method="POST" action="{{ route('cv.templates.destroy', $template) }}" onsubmit="return confirm('Eliminar esta plantilla?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 border rounded text-sm text-red-600">Eliminar</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-sm text-gray-600">Aun no hay plantillas.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
