<?php

namespace Database\Seeders;

use App\Models\CvTemplate;
use Illuminate\Database\Seeder;

class CvTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CvTemplate::updateOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'Classic Blue',
                'description' => 'Plantilla clasica con dos columnas y cabecera azul.',
                'html' => $this->classicHtml(),
                'css' => $this->classicCss(),
                'options' => [
                    'primary_color' => '#003366',
                    'accent_color' => '#0d3b66',
                    'font_family' => "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                    'base_font_size' => '11px',
                    'heading_font_size' => '13px',
                ],
                'is_system' => true,
            ]
        );

        CvTemplate::updateOrCreate(
            ['slug' => 'harvard'],
            [
                'name' => 'Harvard Minimal',
                'description' => 'Formato minimalista estilo Harvard con secciones claras.',
                'html' => $this->harvardHtml(),
                'css' => $this->harvardCss(),
                'options' => [
                    'primary_color' => '#000000',
                    'accent_color' => '#444444',
                    'font_family' => "'Times New Roman', Times, serif",
                    'base_font_size' => '11px',
                    'heading_font_size' => '12px',
                ],
                'is_system' => true,
            ]
        );
    }

    private function classicHtml(): string
    {
        return <<<'HTML'
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
                    @if($profile?->linkedin)
                        <p>LinkedIn: <a href="{{ $profile->linkedin }}">{{ $profile->linkedin }}</a></p>
                    @endif
                    @if($profile?->github)
                        <p>GitHub: <a href="{{ $profile->github }}">{{ $profile->github }}</a></p>
                    @endif
                    @if($profile?->driver_license)
                        <p>Licencia de conducir tipo: {{ $profile->driver_license_type ?? 'Si' }}</p>
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

                    @if($languages->count())
                        <h2>Idiomas</h2>
                        <ul>
                            @foreach($languages as $lang)
                                <li>{{ $lang->name }}@if($lang->level) ({{ $lang->level }})@endif</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($certificates->count())
                        <h2>Certificaciones</h2>
                        <ul class="cv-cert-list">
                            @foreach($certificates as $c)
                                <li class="cv-cert-item">
                                    <div>{{ $c->name }}</div>
                                    <div class="cv-muted">
                                        @if($c->issuer) {{ $c->issuer }} @endif
                                        @if($c->date) - {{ $c->date->format('M Y') }} @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
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
                            <div class="cv-entry">
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
HTML;
    }

    private function classicCss(): string
    {
        return <<<'CSS'
@page {
    margin: 0;
}
.cv-preview-frame {
    background: #e9edf1;
    padding: 20px;
    overflow: auto;
}
.cv-page {
    width: 8.2in;
    min-height: 11in;
    height: auto;
    margin: 0 auto;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}
.cv-shell {
    width: 100%;
    min-height: 100%;
    font-family: var(--font-family, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif);
    font-size: var(--base-font-size, 11px);
    color: #222;
    background-color: #f4f6f8;
}
.cv-shell * {
    box-sizing: border-box;
}
.cv-header {
    background-color: var(--primary-color, #003366);
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
    font-size: var(--heading-font-size, 13px);
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
    display: grid;
    grid-template-columns: 35% 65%;
    width: 100%;
    padding: 0 10mm;
    min-height: calc(11in - 120px);
}
.cv-left,
.cv-right {
    height: 100%;
}
.cv-left {
    padding: 10px;
    border-right: 1px solid #ccc;
    background-color: var(--accent-color, #0d3b66);
    color: #fff;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.cv-left h2 {
    color: #fff;
    border-color: rgba(255, 255, 255, 0.27);
}
.cv-right {
    padding: 10px;
    background-color: #fff;
    box-shadow: 0 0 4px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    gap: 8px;
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
        width: 8.0in;
        min-height: 10.7in;
        height: auto;
        box-shadow: none;
    }
    .cv-shell {
        background-color: #fff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-size: 10px;
    }
    .cv-container {
        padding: 0;
        min-height: 10.5in;
    }
    .cv-header h1 { font-size: 24px; }
    .cv-header p { font-size: 12px; }
    .cv-shell h2 { margin: 10px 0 5px; }
    .cv-right, .cv-left { padding: 8px; }
    .cv-shell p, .cv-shell li { line-height: 1.35; }
}
CSS;
    }

    private function harvardHtml(): string
    {
        return <<<'HTML'
<div class="cv-preview-frame harvard-frame">
    <div class="cv-page harvard-page">
        <div class="harvard-shell">
            <header class="harvard-header">
                <div class="harvard-name">{{ $fullName }}</div>
                <div class="harvard-contact">{{ $contactLine }}</div>
                @if($profile?->driver_license)
                    <div class="harvard-contact">Licencia de conducir tipo: {{ $profile->driver_license_type ?? 'Yes' }}</div>
                @endif
            </header>

            @if($educations->count())
                <section class="harvard-section">
                    <h2>Education</h2>
                    @foreach($educations as $edu)
                        <div class="harvard-row">
                            <div class="harvard-left">
                                <div class="harvard-strong">{{ $edu->institution }}</div>
                                <div>{{ $edu->degree }}</div>
                                @if($edu->description)
                                    <div class="harvard-note">{!! nl2br(e($edu->description)) !!}</div>
                                @endif
                            </div>
                            <div class="harvard-right">
                                <div>{{ $edu->location }}</div>
                                <div>
                                    {{ optional($edu->start_date)->format('M Y') }}
                                    @if($edu->current) - Present @else - {{ optional($edu->end_date)->format('M Y') }} @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </section>
            @endif

            @if($experiences->count())
                <section class="harvard-section">
                    <h2>Experience</h2>
                    @foreach($experiences as $exp)
                        @php
                            $lines = $exp->description ? preg_split('/\r\n|\r|\n/', trim($exp->description)) : [];
                        @endphp
                        <div class="harvard-row">
                            <div class="harvard-left">
                                <div class="harvard-strong">{{ $exp->company ?: 'Organization' }}</div>
                                <div>{{ $exp->title }}</div>
                            </div>
                            <div class="harvard-right">
                                <div>{{ $exp->location ?: '' }}</div>
                                <div>
                                    {{ optional($exp->start_date)->format('M Y') }}
                                    @if($exp->current) - Present @else - {{ optional($exp->end_date)->format('M Y') }} @endif
                                </div>
                            </div>
                        </div>
                        @if($exp->description)
                            <ul class="harvard-bullets">
                                @foreach($lines as $line)
                                    @if(trim($line) !== '')
                                        <li>{{ $line }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </section>
            @endif

            @if($certificates->count())
                <section class="harvard-section">
                    <h2>Certifications</h2>
                    @foreach($certificates as $c)
                        <div class="harvard-row">
                            <div class="harvard-left">
                                <div class="harvard-strong">{{ $c->name }}</div>
                                @if($c->issuer) <div>{{ $c->issuer }}</div> @endif
                            </div>
                            <div class="harvard-right">
                                <div>{{ $c->date ? $c->date->format('M Y') : '' }}</div>
                            </div>
                        </div>
                    @endforeach
                </section>
            @endif

            @if($skills->count())
                <section class="harvard-section">
                    <h2>Skills & Interests</h2>
                    <p class="harvard-inline">
                        @foreach($skills as $s)
                            <span>{{ $s->name }}@if($s->level) ({{ $s->level }})@endif</span>@if(!$loop->last), @endif
                        @endforeach
                    </p>
                </section>
            @endif

            @if($languages->count())
                <section class="harvard-section">
                    <h2>Languages</h2>
                    <ul class="harvard-inline">
                        @foreach($languages as $lang)
                            <li>{{ $lang->name }}@if($lang->level) ({{ $lang->level }})@endif</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</div>
HTML;
    }

    private function harvardCss(): string
    {
        return <<<'CSS'
@page {
    margin: 0;
}
.harvard-frame {
    background: #f5f5f5;
    padding: 18px;
}
.harvard-page {
    width: 8.2in;
    min-height: 11in;
    height: auto;
    margin: 0 auto;
    background: #ffffff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    padding: 0.35in 0.55in;
    overflow: hidden;
}
.harvard-shell {
    font-family: var(--font-family, 'Times New Roman', Times, serif);
    font-size: var(--base-font-size, 11px);
    color: #111;
}
.harvard-header {
    text-align: center;
    border-bottom: 1px solid #222;
    padding-bottom: 8px;
    margin-bottom: 14px;
}
.harvard-name {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.harvard-contact {
    font-size: 10px;
    color: #333;
    margin-top: 4px;
}
.harvard-section h2 {
    font-size: var(--heading-font-size, 12px);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin: 12px 0 6px;
    border-bottom: 1px solid #222;
    padding-bottom: 3px;
}
.harvard-row {
    display: table;
    width: 100%;
    table-layout: fixed;
    margin-top: 6px;
}
.harvard-left {
    display: table-cell;
    vertical-align: top;
}
.harvard-right {
    display: table-cell;
    width: 180px;
    text-align: right;
    color: #333;
    vertical-align: top;
}
.harvard-strong {
    font-weight: 700;
}
.harvard-bullets {
    margin: 4px 0 8px 16px;
}
.harvard-bullets li {
    margin: 2px 0;
    line-height: 1.4;
}
.harvard-note {
    font-size: 10px;
    color: #444;
}
.harvard-inline {
    margin: 4px 0;
}
@media print {
    .harvard-frame {
        padding: 0;
        background: #fff;
    }
    .harvard-page {
        width: 8.0in;
        min-height: 10.7in;
        height: auto;
        box-shadow: none;
        padding: 0.35in 0.55in;
    }
    .harvard-shell { font-size: 10px; }
    .harvard-name { font-size: 16px; }
    .harvard-contact { font-size: 9px; }
    .harvard-section h2 { margin: 10px 0 6px; }
    .harvard-bullets li { line-height: 1.35; }
}
CSS;
    }
}
