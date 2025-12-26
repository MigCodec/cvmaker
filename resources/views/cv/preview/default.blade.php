<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Previsualizacion</h2>
</x-slot>

@php
    $experienceIds = $experiences->pluck('id')->map(fn ($id) => (string) $id)->values();
    $certificateIds = $certificates->pluck('id')->map(fn ($id) => (string) $id)->values();
    $fullName = trim(($profile?->first_name ?? $user->name) . ' ' . ($profile?->last_name ?? ''));
    $headline = $targetRole ?? $profile?->headline;
@endphp

<div class="py-8 bg-gray-100 px-4 sm:px-6 lg:px-8 print:bg-white">
    <div class="mx-auto w-full max-w-6xl flex flex-col md:flex-row gap-6 cv-layout" x-data="{
        selectedExperiences: @js($experienceIds),
        selectedCertificates: @js($certificateIds),
        allExperiences: @js($experienceIds),
        allCertificates: @js($certificateIds)
    }">
        <aside class="w-full md:w-72 lg:w-80 md:shrink-0 bg-white shadow border border-gray-200 rounded-xl p-4 space-y-6 print:hidden cv-sidebar">
            <div>
                <h3 class="text-sm font-semibold uppercase text-gray-700">Experiencias</h3>
                <div class="mt-2 flex items-center gap-2 text-xs">
                    <button type="button" class="text-blue-600 hover:underline" @click="selectedExperiences = allExperiences.slice()">Mostrar todo</button>
                    <button type="button" class="text-gray-500 hover:underline" @click="selectedExperiences = []">Limpiar</button>
                </div>
                <div class="mt-3 space-y-2 max-h-72 overflow-auto pr-1">
                    @forelse($experiences as $exp)
                        <label class="flex items-start gap-2 text-sm text-gray-700">
                            <input type="checkbox" value="{{ $exp->id }}" x-model="selectedExperiences" class="mt-1 rounded border-gray-300" />
                            <span>
                                <span class="font-medium">{{ $exp->title }}</span>
                                @if($exp->company) <span class="text-gray-500">- {{ $exp->company }}</span> @endif
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">No hay experiencias registradas.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase text-gray-700">Certificados</h3>
                <div class="mt-2 flex items-center gap-2 text-xs">
                    <button type="button" class="text-blue-600 hover:underline" @click="selectedCertificates = allCertificates.slice()">Mostrar todo</button>
                    <button type="button" class="text-gray-500 hover:underline" @click="selectedCertificates = []">Limpiar</button>
                </div>
                <div class="mt-3 space-y-2 max-h-72 overflow-auto pr-1">
                    @forelse($certificates as $c)
                        <label class="flex items-start gap-2 text-sm text-gray-700">
                            <input type="checkbox" value="{{ $c->id }}" x-model="selectedCertificates" class="mt-1 rounded border-gray-300" />
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
        </aside>

        <div class="flex-1">
            <div class="bg-white shadow border border-gray-200 rounded-xl overflow-hidden print:rounded-none print:shadow-none print:border-0">
                <style>
                    @page { margin: 0; }
                    .cv-preview-frame {
                        background: #e9edf1;
                        padding: 20px;
                        overflow: auto;
                    }
                    .cv-page {
                        width: 8.5in;
                        height: 11in;
                        margin: 0 auto;
                        background: #fff;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                        overflow: hidden;
                    }
                    .cv-shell {
                        width: 100%;
                        height: 100%;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        font-size: 11px;
                        color: #222;
                        background-color: #f4f6f8;
                    }
                    .cv-header {
                        background-color: #003366;
                        color: #fff;
                        text-align: center;
                        padding: 16px 8px;
                    }
                    .cv-header h1 {
                        font-size: 28px;
                        margin-bottom: 4px;
                    }
                    .cv-header p {
                        font-size: 14px;
                    }
                    .cv-shell h2 {
                        font-size: 13px;
                        margin: 12px 0 6px;
                        border-bottom: 1px solid #ccc;
                        padding-bottom: 2px;
                    }
                    .cv-shell h3 {
                        font-size: 12px;
                        margin: 4px 0 2px;
                    }
                    .cv-shell p,
                    .cv-shell li {
                        margin: 2px 0;
                        line-height: 1.4;
                    }
                    .cv-container {
                        display: flex;
                        gap: 0;
                        padding: 0 10mm;
                    }
                    .cv-left {
                        width: 35%;
                        padding: 10px;
                        border-right: 1px solid #ccc;
                        background-color: #0d3b66;
                        color: #fff;
                    }
                    .cv-left h2 {
                        color: #fff;
                        border-color: #ffffff44;
                    }
                    .cv-right {
                        width: 65%;
                        padding: 10px;
                        background-color: #fff;
                        box-shadow: 0 0 4px rgba(0, 0, 0, 0.05);
                    }
                    .cv-shell ul {
                        padding-left: 16px;
                        margin-bottom: 8px;
                    }
                    .cv-shell a {
                        color: #a2c4f0;
                        text-decoration: none;
                        word-break: break-all;
                    }
                    .job-title {
                        font-weight: bold;
                        color: #0d3b66;
                    }
                    .job-place {
                        font-style: italic;
                        color: #555;
                    }
                    .cv-cert-list {
                        list-style: none;
                        padding-left: 0;
                    }
                    .cv-cert-item {
                        margin-bottom: 10px;
                    }
                    .cv-muted {
                        font-size: 11px;
                        color: #e6efff;
                    }
                    .cv-right .cv-muted {
                        color: #777;
                    }
                    @media print {
                        .cv-preview-frame {
                            padding: 0;
                            background: #fff;
                        }
                        .cv-page {
                            width: 100%;
                            height: 100%;
                            box-shadow: none;
                        }
                        .cv-shell {
                            background-color: #fff;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        .cv-container {
                            padding: 0;
                        }
                        .cv-layout {
                            display: block;
                        }
                        .cv-sidebar,
                        .cv-actions {
                            display: none !important;
                        }
                    }
                </style>

                <div class="cv-preview-frame">
                    <div class="cv-page">
                        <div class="cv-shell">
                            <header class="cv-header">
                                <h1>{{ $fullName }}</h1>
                                @if($headline)
                                    <p><strong>{{ $headline }}</strong></p>
                                @endif
                            </header>

                            <div class="cv-container">
                                <aside class="cv-left">
                                    <h2>Contacto</h2>
                                    @if($user->email)
                                        <p>Email: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                                    @endif
                                    @if($profile?->phone)
                                        <p>Telefono: {{ $profile->phone }}</p>
                                    @endif
                                    @if($profile?->location)
                                        <p>Ubicacion: {{ $profile->location }}</p>
                                    @endif
                                    @if($profile?->website)
                                        <p>Sitio: <a href="{{ $profile->website }}">{{ $profile->website }}</a></p>
                                    @endif

                                    @if($summary)
                                        <h2>Perfil</h2>
                                        <p>{!! nl2br(e($summary)) !!}</p>
                                    @endif

                                    @if($skills->count())
                                        <h2>Habilidades</h2>
                                        <ul>
                                            @foreach($skills as $s)
                                                <li>{{ $s->name }}@if($s->level) ({{ $s->level }})@endif</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if($certificates->count())
                                        <h2>Certificaciones</h2>
                                        <ul class="cv-cert-list">
                                            @foreach($certificates as $c)
                                                <li class="cv-cert-item" x-show="selectedCertificates.includes('{{ $c->id }}')">
                                                    <div>{{ $c->name }}</div>
                                                    <div>
                                                        @if($c->issuer) {{ $c->issuer }} @endif
                                                        @if($c->date) - {{ $c->date->format('M Y') }} @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <p class="cv-muted print:hidden" x-show="selectedCertificates.length === 0">No hay certificados seleccionados.</p>
                                    @endif
                                </aside>

                                <main class="cv-right">
                                    @if($experiences->count())
                                        <h2>Experiencia</h2>
                                        @foreach($experiences as $exp)
                                            @php
                                                $startLabel = optional($exp->start_date)->format('M Y');
                                                $endLabel = $exp->current ? 'Actualidad' : optional($exp->end_date)->format('M Y');
                                                $lines = $exp->description ? preg_split('/\r\n|\r|\n/', trim($exp->description)) : [];
                                            @endphp
                                            <div class="cv-entry" x-show="selectedExperiences.includes('{{ $exp->id }}')">
                                                <h3 class="job-title">{{ $exp->title }}</h3>
                                                @if($exp->company || $startLabel || $endLabel)
                                                    <p class="job-place">
                                                        @if($exp->company) {{ $exp->company }} @endif
                                                        @if($exp->company && ($startLabel || $endLabel)) - @endif
                                                        @if($startLabel || $endLabel)
                                                            {{ $startLabel }}@if($startLabel && $endLabel) a @endif{{ $endLabel }}
                                                        @endif
                                                    </p>
                                                @endif
                                                @if($exp->location)
                                                    <p>{{ $exp->location }}</p>
                                                @endif
                                                @if($exp->description)
                                                    <ul>
                                                        @foreach($lines as $line)
                                                            @if(trim($line) !== '')
                                                                <li>{{ $line }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                        <p class="cv-muted print:hidden" x-show="selectedExperiences.length === 0">No hay experiencias seleccionadas.</p>
                                    @endif

                                    @if($educations->count())
                                        <h2>Educacion</h2>
                                        @foreach($educations as $edu)
                                            @php
                                                $eduStart = optional($edu->start_date)->format('M Y');
                                                $eduEnd = $edu->current ? 'Actualidad' : optional($edu->end_date)->format('M Y');
                                            @endphp
                                            <div class="cv-entry">
                                                <h3>{{ $edu->degree }}</h3>
                                                @if($edu->institution || $eduStart || $eduEnd)
                                                    <p>
                                                        @if($edu->institution) {{ $edu->institution }} @endif
                                                        @if($edu->institution && ($eduStart || $eduEnd)) - @endif
                                                        @if($eduStart || $eduEnd)
                                                            {{ $eduStart }}@if($eduStart && $eduEnd) a @endif{{ $eduEnd }}
                                                        @endif
                                                    </p>
                                                @endif
                                                @if($edu->location)
                                                    <p>{{ $edu->location }}</p>
                                                @endif
                                                @if($edu->description)
                                                    <p>{!! nl2br(e($edu->description)) !!}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </main>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-end gap-2 print:hidden cv-actions">
                <a href="{{ route('cv.profile.edit') }}" class="px-4 py-2 border rounded">Editar datos</a>
                @if($cv)
                    <a href="{{ route('cv.pdf', $cv) }}" class="px-4 py-2 bg-blue-600 text-white rounded">Descargar PDF</a>
                @else
                    <a href="{{ route('cv.pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Descargar PDF</a>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
