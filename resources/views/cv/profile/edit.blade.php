<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Armar CV</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('cv.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="p-6">
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Paso 1 - Perfil</p>
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-2xl font-semibold">Datos de perfil</h1>
                            <x-primary-button type="submit">Guardar</x-primary-button>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Completa solo la informacion personal. Las demas secciones se agregan aparte para mantener el CV segmentado.</p>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Nombre</label>
                                    <input name="first_name" value="{{ old('first_name', $profile->first_name) }}" class="mt-1 w-full border rounded p-2" />
                                    @error('first_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Apellido</label>
                                    <input name="last_name" value="{{ old('last_name', $profile->last_name) }}" class="mt-1 w-full border rounded p-2" />
                                    @error('last_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium">Titulo / Headline</label>
                                    <input name="headline" value="{{ old('headline', $profile->headline) }}" class="mt-1 w-full border rounded p-2" />
                                    @error('headline') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Resumen</label>
                                <textarea name="summary" rows="4" class="mt-1 w-full border rounded p-2">{{ old('summary', $profile->summary) }}</textarea>
                                @error('summary') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Telefono</label>
                                    <input name="phone" value="{{ old('phone', $profile->phone) }}" class="mt-1 w-full border rounded p-2" />
                                    @error('phone') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Ubicacion</label>
                                    <input name="location" value="{{ old('location', $profile->location) }}" class="mt-1 w-full border rounded p-2" />
                                    @error('location') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium">Sitio web</label>
                                    <input name="website" value="{{ old('website', $profile->website) }}" class="mt-1 w-full border rounded p-2" />
                                    @error('website') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">LinkedIn</label>
                                    <input name="linkedin" value="{{ old('linkedin', $profile->linkedin) }}" class="mt-1 w-full border rounded p-2" placeholder="https://linkedin.com/in/usuario" />
                                    @error('linkedin') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">GitHub</label>
                                    <input name="github" value="{{ old('github', $profile->github) }}" class="mt-1 w-full border rounded p-2" placeholder="https://github.com/usuario" />
                                    @error('github') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Licencia de conducir</label>
                                    <div class="mt-2 flex items-center gap-2">
                                        <input type="checkbox" id="driver-license" name="driver_license" value="1" {{ old('driver_license', $profile->driver_license) ? 'checked' : '' }} />
                                        <span class="text-sm text-gray-700">Cuenta con licencia</span>
                                    </div>
                                    @error('driver_license') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Tipo de licencia</label>
                                    <input id="driver-license-type" name="driver_license_type" value="{{ old('driver_license_type', $profile->driver_license_type) }}" class="mt-1 w-full border rounded p-2" placeholder="Ej: B, A2, C" />
                                    @error('driver_license_type') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Plantilla</label>
                                    <select name="template" class="mt-1 w-full border rounded p-2">
                                        @php($tpl = old('template', $profile->template ?? 'default'))
                                        @foreach($templates as $template)
                                            <option value="{{ $template->slug }}" {{ $tpl === $template->slug ? 'selected' : '' }}>
                                                {{ $template->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('template') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <x-primary-button type="submit">Guardar</x-primary-button>
                                <a class="px-4 py-2 border rounded" href="{{ route('cvs.index') }}">Ir a mis CVs</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-white shadow sm:rounded-lg space-y-2">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Paso 2</div>
                    <div class="text-lg font-semibold">Experiencia</div>
                    <p class="text-sm text-gray-600">Registra cada puesto. Items: {{ $user->experiences_count }}</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('cv.experiences.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.experiences.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>
                <div class="p-4 bg-white shadow sm:rounded-lg space-y-2">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Paso 3</div>
                    <div class="text-lg font-semibold">Educacion</div>
                    <p class="text-sm text-gray-600">Agrega estudios por separado. Items: {{ $user->educations_count }}</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('cv.educations.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.educations.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>
                <div class="p-4 bg-white shadow sm:rounded-lg space-y-2">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Paso 4</div>
                    <div class="text-lg font-semibold">Certificados</div>
                    <p class="text-sm text-gray-600">Carga certificaciones individuales. Items: {{ $user->certificates_count }}</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('cv.certificates.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.certificates.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>
                <div class="p-4 bg-white shadow sm:rounded-lg space-y-2">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Paso 5</div>
                    <div class="text-lg font-semibold">Habilidades</div>
                    <p class="text-sm text-gray-600">Lista tus skills y el orden. Items: {{ $user->skills_count }}</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('cv.skills.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.skills.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var checkbox = document.getElementById('driver-license');
        var input = document.getElementById('driver-license-type');
        if (!checkbox || !input) return;
        var toggle = function () {
            if (checkbox.checked) {
                input.removeAttribute('disabled');
            } else {
                input.value = '';
                input.setAttribute('disabled', 'disabled');
            }
        };
        checkbox.addEventListener('change', toggle);
        toggle();
    });
    </script>
</x-app-layout>
