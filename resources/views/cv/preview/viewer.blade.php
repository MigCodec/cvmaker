@php
    $query = request()->query();
    $pdfUrl = $cv ? route('cv.pdf', $cv) : route('cv.pdf');
    if (!empty($query)) {
        $pdfUrl .= '?' . http_build_query($query);
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between print-hide">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Previsualizacion</h2>
            <div class="flex items-center gap-2">
                <button type="button" id="toggle-sidebar" class="px-3 py-1 border rounded text-sm">Opciones</button>
                <button type="button" id="print-preview" class="px-4 py-2 border rounded text-sm">Imprimir</button>
                <a href="{{ $pdfUrl }}" class="px-4 py-2 bg-blue-600 text-white rounded text-sm">Descargar PDF</a>
            </div>
        </div>
    </x-slot>

    @php
        $query = request()->query();
        $pdfUrl = $cv ? route('cv.pdf', $cv) : route('cv.pdf');
        if (!empty($query)) {
            $pdfUrl .= '?' . http_build_query($query);
        }
    @endphp

    <div class="py-8 bg-gray-100 px-4 sm:px-6 lg:px-8 print:bg-white">
        <div class="mx-auto w-full max-w-6xl flex flex-col md:flex-row gap-6 relative">
            <style>
                .sidebar-panel {
                    transition: transform 0.2s ease, opacity 0.2s ease;
                }
                @media (max-width: 767px) {
                    .sidebar-panel {
                        position: absolute;
                        left: 0;
                        top: 0;
                        height: 100%;
                        z-index: 40;
                        width: 18rem;
                    }
                    .sidebar-panel.sidebar-collapsed {
                        transform: translateX(-110%);
                        opacity: 0;
                    }
                }
                /* Print only the CV content */
                @media print {
                    body, html { background: #fff !important; }
                    nav, header, footer, #viewer-sidebar, .print-hide { display: none !important; }
                    body * { visibility: hidden; }
                    #print-area, #print-area * { visibility: visible; }
                    #print-area { position: absolute; left: 0; top: 0; width: 100%; }
                }
            </style>
            <aside id="viewer-sidebar" class="sidebar-panel w-full md:w-72 lg:w-80 md:shrink-0 bg-white shadow border border-gray-200 rounded-xl p-4 space-y-6 print-hide md:static md:opacity-100 md:transform-none">
                <form method="GET" action="{{ route('cv.preview', $cv ?? null) }}" class="space-y-6" id="cv-preview-controls">
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-gray-700">Plantilla</h3>
                        <select name="template" class="mt-2 w-full border rounded p-2 text-sm">
                            @foreach($templates as $item)
                                <option value="{{ $item->slug }}" {{ $template->slug === $item->slug ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-gray-700">Experiencias</h3>
                        <div class="mt-2 space-y-2 max-h-72 overflow-auto pr-1">
                            @forelse($allExperiences as $exp)
                                <label class="flex items-start gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="experiences[]" value="{{ $exp->id }}" class="mt-1 rounded border-gray-300" {{ $selectedExperiences->contains($exp->id) ? 'checked' : '' }} />
                                    <span class="flex-1">
                                        <span class="font-medium">{{ $exp->title }}</span>
                                        @if($exp->company) <span class="text-gray-500">- {{ $exp->company }}</span> @endif
                                    </span>
                                    <input type="number" name="experience_order[{{ $exp->id }}]" value="{{ $experienceOrder[$exp->id] ?? '' }}" class="w-16 border rounded p-1 text-xs" min="1" placeholder="Ord" />
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No hay experiencias registradas.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold uppercase text-gray-700">Certificados</h3>
                        <div class="mt-2 space-y-2 max-h-72 overflow-auto pr-1">
                            @forelse($allCertificates as $c)
                                <label class="flex items-start gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="certificates[]" value="{{ $c->id }}" class="mt-1 rounded border-gray-300" {{ $selectedCertificates->contains($c->id) ? 'checked' : '' }} />
                                    <span>
                                        <span class="font-medium">{{ $c->name }}</span>
                                        @if($c->issuer) <span class="text-gray-500">- {{ $c->issuer }}</span> @endif
                                    </span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No hay certificados registrados.</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-gray-700">Habilidades</h3>
                        <div class="mt-2 space-y-2 max-h-64 overflow-auto pr-1">
                            @forelse($allSkills as $skill)
                                <label class="flex items-start gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="mt-1 rounded border-gray-300" {{ $selectedSkills->contains($skill->id) ? 'checked' : '' }} />
                                    <span class="flex-1">
                                        <span class="font-medium">{{ $skill->name }}</span>
                                        @if($skill->level) <span class="text-gray-500">- {{ $skill->level }}</span> @endif
                                    </span>
                                    <input type="number" name="skills_order[{{ $skill->id }}]" value="{{ $skillsOrder[$skill->id] ?? '' }}" class="w-16 border rounded p-1 text-xs" min="1" placeholder="Ord" />
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No hay habilidades registradas.</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold uppercase text-gray-700">Idiomas</h3>
                        <div class="mt-2 space-y-2 max-h-64 overflow-auto pr-1">
                            @forelse($allLanguages as $lang)
                                <label class="flex items-start gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="languages[]" value="{{ $lang->id }}" class="mt-1 rounded border-gray-300" {{ $selectedLanguages->contains($lang->id) ? 'checked' : '' }} />
                                    <span class="flex-1">
                                        <span class="font-medium">{{ $lang->name }}</span>
                                        @if($lang->level) <span class="text-gray-500">- {{ $lang->level }}</span> @endif
                                    </span>
                                    <input type="number" name="languages_order[{{ $lang->id }}]" value="{{ $languagesOrder[$lang->id] ?? '' }}" class="w-16 border rounded p-1 text-xs" min="1" placeholder="Ord" />
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No hay idiomas registrados.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Aplicar</button>
                        <a href="{{ route('cv.preview', $cv ?? null) }}" class="px-4 py-2 border rounded">Limpiar</a>
                    </div>
                </form>
            </aside>

            <div class="flex-1">
                <div id="print-area" class="bg-white shadow border border-gray-200 rounded-xl overflow-hidden print:rounded-none print:shadow-none print:border-0">
                    {!! $renderedHtml !!}
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 print:hidden">
                    <a href="{{ route('cv.profile.edit') }}" class="px-4 py-2 border rounded">Editar datos</a>
                    <a href="{{ $pdfUrl }}" class="px-4 py-2 bg-blue-600 text-white rounded">Descargar PDF</a>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ $pdfUrl }}" class="fixed bottom-4 right-4 px-4 py-2 bg-blue-600 text-white rounded shadow-lg hover:bg-blue-700 focus:outline-none print-hide">
        Descargar PDF
    </a>

    <script>
    (() => {
        const form = document.getElementById('cv-preview-controls');
        const sidebar = document.getElementById('viewer-sidebar');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const printBtn = document.getElementById('print-preview');
        if (!form) return;
        let timer = null;
        const scheduleSubmit = () => {
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 250);
        };
        form.addEventListener('change', scheduleSubmit);
        toggleBtn?.addEventListener('click', () => {
            if (!sidebar) return;
            sidebar.classList.toggle('sidebar-collapsed');
        });
        // open by default on desktop, collapsed on mobile
        if (window.innerWidth < 768 && sidebar) {
            sidebar.classList.add('sidebar-collapsed');
        } else if (sidebar) {
            sidebar.classList.remove('sidebar-collapsed');
        }
        printBtn?.addEventListener('click', () => {
            const area = document.getElementById('print-area');
            if (!area) return window.print();
            const win = window.open('', '_blank', 'width=900,height=1200');
            if (!win) return;

            // Abrir ventana solo con el HTML del CV y un reset mínimo (sin otros estilos que agregan márgenes)
            const doc = `<!doctype html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <title>CV</title>
                    <style>
                        @page { margin: 0; }
                        html, body {
                            margin: 0;
                            padding: 0;
                            width: 100%;
                            min-height: 100%;
                            background: #f4f6f8;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        * {
                            box-sizing: border-box;
                        }
                    </style>
                </head>
                <body>
                    ${area.innerHTML}
                </body>
            </html>`;

            win.document.open();
            win.document.write(doc);
            win.document.close();
            win.onload = () => {
                win.focus();
            };
        });
    })();
    </script>
</x-app-layout>
