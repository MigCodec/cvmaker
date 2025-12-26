@php
    $selectedExperiences = collect(old('experiences', isset($cv) ? $cv->experiences->pluck('id')->all() : []));
    $selectedEducations = collect(old('educations', isset($cv) ? $cv->educations->pluck('id')->all() : []));
    $selectedCertificates = collect(old('certificates', isset($cv) ? $cv->certificates->pluck('id')->all() : []));
    $selectedSkills = collect(old('skills', isset($cv) ? $cv->skills->pluck('id')->all() : []));
    $selectedLanguages = collect(old('languages', isset($cv) ? $cv->languages->pluck('id')->all() : []));
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Nombre del CV</label>
            <input name="name" value="{{ old('name', $cv->name ?? '') }}" class="mt-1 w-full border rounded p-2" required />
            @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Rol objetivo</label>
            <input name="target_role" value="{{ old('target_role', $cv->target_role ?? '') }}" class="mt-1 w-full border rounded p-2" placeholder="Ej: Desarrollador Backend" />
            @error('target_role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium">Resumen (opcional, reemplaza el del perfil)</label>
            <textarea name="summary" rows="3" class="mt-1 w-full border rounded p-2" placeholder="Personaliza un resumen para esta postulacion">{{ old('summary', $cv->summary ?? '') }}</textarea>
            @error('summary') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Plantilla</label>
            @php($tpl = old('template', $cv->template ?? 'default'))
            <select name="template" class="mt-1 w-full border rounded p-2">
                @foreach($templates as $template)
                    <option value="{{ $template->slug }}" {{ $tpl === $template->slug ? 'selected' : '' }}>
                        {{ $template->name }}
                    </option>
                @endforeach
            </select>
            @error('template') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="bg-gray-50 border rounded p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Experiencia</p>
                    <p class="text-sm text-gray-600">Selecciona las experiencias que quieres incluir.</p>
                </div>
                <a class="text-blue-600 text-sm" href="{{ route('cv.experiences.create') }}">Agregar experiencia</a>
            </div>
            <div class="space-y-2">
                @forelse($experiences as $experience)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="experiences[]" value="{{ $experience->id }}" {{ $selectedExperiences->contains($experience->id) ? 'checked' : '' }}>
                        <span>
                            <span class="font-medium">{{ $experience->title }}</span>
                            @if($experience->company) <span class="text-gray-600">- {{ $experience->company }}</span> @endif
                            <div class="text-gray-500 text-xs">{{ optional($experience->start_date)->format('M Y') }} - {{ $experience->current ? 'Actualidad' : optional($experience->end_date)->format('M Y') }}</div>
                        </span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">Aun no has cargado experiencias.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-50 border rounded p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Educacion</p>
                    <p class="text-sm text-gray-600">Elige los estudios relevantes.</p>
                </div>
                <a class="text-blue-600 text-sm" href="{{ route('cv.educations.create') }}">Agregar educacion</a>
            </div>
            <div class="space-y-2">
                @forelse($educations as $education)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="educations[]" value="{{ $education->id }}" {{ $selectedEducations->contains($education->id) ? 'checked' : '' }}>
                        <span>
                            <span class="font-medium">{{ $education->degree }}</span>
                            <span class="text-gray-600">- {{ $education->institution }}</span>
                            <div class="text-gray-500 text-xs">{{ optional($education->start_date)->format('M Y') }} - {{ $education->current ? 'Actualidad' : optional($education->end_date)->format('M Y') }}</div>
                        </span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">Aun no has cargado educacion.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-50 border rounded p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Certificados</p>
                    <p class="text-sm text-gray-600">Incluye certificaciones pertinentes.</p>
                </div>
                <a class="text-blue-600 text-sm" href="{{ route('cv.certificates.create') }}">Agregar certificado</a>
            </div>
            <div class="space-y-2">
                @forelse($certificates as $certificate)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="certificates[]" value="{{ $certificate->id }}" {{ $selectedCertificates->contains($certificate->id) ? 'checked' : '' }}>
                        <span>
                            <span class="font-medium">{{ $certificate->name }}</span>
                            @if($certificate->issuer) <span class="text-gray-600">- {{ $certificate->issuer }}</span> @endif
                            <div class="text-gray-500 text-xs">{{ $certificate->date ? $certificate->date->format('M Y') : 'Sin fecha' }}</div>
                        </span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">Aun no has cargado certificados.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-50 border rounded p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Habilidades</p>
                    <p class="text-sm text-gray-600">Marca las skills a mostrar.</p>
                </div>
                <a class="text-blue-600 text-sm" href="{{ route('cv.skills.create') }}">Agregar habilidad</a>
            </div>
            <div class="space-y-2">
                @forelse($skills as $skill)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}" {{ $selectedSkills->contains($skill->id) ? 'checked' : '' }}>
                        <span>
                            <span class="font-medium">{{ $skill->name }}</span>
                            @if($skill->level) <span class="text-gray-600">- {{ $skill->level }}</span> @endif
                        </span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">Aun no has cargado habilidades.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-50 border rounded p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Idiomas</p>
                    <p class="text-sm text-gray-600">Selecciona los idiomas a incluir.</p>
                </div>
                <a class="text-blue-600 text-sm" href="{{ route('cv.languages.create') }}">Agregar idioma</a>
            </div>
            <div class="space-y-2">
                @forelse($languages as $language)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="languages[]" value="{{ $language->id }}" {{ $selectedLanguages->contains($language->id) ? 'checked' : '' }}>
                        <span>
                            <span class="font-medium">{{ $language->name }}</span>
                            @if($language->level) <span class="text-gray-600">- {{ $language->level }}</span> @endif
                        </span>
                    </label>
                @empty
                    <p class="text-gray-500 text-sm">Aun no has cargado idiomas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
