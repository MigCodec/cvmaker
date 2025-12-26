@php
    $options = $template->options ?? [];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Nombre</label>
                <input name="name" value="{{ old('name', $template->name) }}" class="mt-1 w-full border rounded p-2" required />
                @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" value="{{ old('slug', $template->slug) }}" class="mt-1 w-full border rounded p-2" placeholder="mi-plantilla" />
                @error('slug') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Descripcion</label>
                <input name="description" value="{{ old('description', $template->description) }}" class="mt-1 w-full border rounded p-2" />
                @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-gray-50 border rounded p-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Ajustes visuales</h3>
            <p class="text-xs text-gray-500 mt-1">Estos valores se inyectan como variables CSS.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                <div>
                    <label class="block text-sm font-medium">Color principal</label>
                    <input type="color" name="options[primary_color]" value="{{ old('options.primary_color', $options['primary_color'] ?? '#003366') }}" class="mt-1 h-10 w-full border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Color secundario</label>
                    <input type="color" name="options[accent_color]" value="{{ old('options.accent_color', $options['accent_color'] ?? '#0d3b66') }}" class="mt-1 h-10 w-full border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Fuente base</label>
                    <input name="options[font_family]" value="{{ old('options.font_family', $options['font_family'] ?? "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif") }}" class="mt-1 w-full border rounded p-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Tamaño base</label>
                    <input name="options[base_font_size]" value="{{ old('options.base_font_size', $options['base_font_size'] ?? '11px') }}" class="mt-1 w-full border rounded p-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Tamaño titulos</label>
                    <input name="options[heading_font_size]" value="{{ old('options.heading_font_size', $options['heading_font_size'] ?? '13px') }}" class="mt-1 w-full border rounded p-2" />
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">HTML</label>
            <p class="text-xs text-gray-500 mt-1">
                Puedes usar Blade: {{ '$fullName' }}, {{ '$headline' }}, {{ '$contactLine' }},
                {{ '$experiences' }}, {{ '$educations' }}, {{ '$certificates' }}, {{ '$skills' }}.
            </p>
            <textarea name="html" rows="12" class="mt-1 w-full border rounded p-2 font-mono text-sm" required>{{ old('html', $template->html) }}</textarea>
            @error('html') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">CSS</label>
            <textarea name="css" rows="10" class="mt-1 w-full border rounded p-2 font-mono text-sm">{{ old('css', $template->css) }}</textarea>
            @error('css') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Preview</h3>
                <p class="text-xs text-gray-500">Se actualiza automaticamente con datos de ejemplo.</p>
            </div>
            <button type="button" id="refresh-preview" class="px-3 py-1 border rounded text-sm">Actualizar</button>
        </div>
        <div class="border rounded overflow-hidden bg-white">
            <iframe id="template-preview" title="Preview de plantilla" class="w-full" style="min-height: 900px;" sandbox="allow-same-origin"></iframe>
        </div>
    </div>
</div>

<script>
(() => {
    const form = document.getElementById('template-form');
    const preview = document.getElementById('template-preview');
    const refresh = document.getElementById('refresh-preview');
    if (!form || !preview) return;

    const previewUrl = form.dataset.previewUrl;
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    let timer = null;

    const updatePreview = () => {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const formData = new FormData(form);
            const response = await fetch(previewUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token || '',
                    'Accept': 'application/json'
                },
                body: formData
            });
            if (!response.ok) return;
            const data = await response.json();
            preview.srcdoc = data.html || '';
        }, 300);
    };

    form.addEventListener('input', updatePreview);
    refresh?.addEventListener('click', updatePreview);
    updatePreview();
})();
</script>
