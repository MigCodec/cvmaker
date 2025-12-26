<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">Bienvenido</p>
                    <h1 class="text-2xl font-semibold mb-2">Armemos tu CV paso a paso</h1>
                    <p class="text-sm text-gray-600">Completa cada seccion de forma separada para mantener tus datos organizados y agregar mas informacion cuando lo necesites.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white shadow sm:rounded-lg p-5 space-y-2">
                    <p class="text-xs text-gray-500 uppercase">Paso 1</p>
                    <h3 class="text-lg font-semibold">Perfil</h3>
                    <p class="text-sm text-gray-600">Datos personales, resumen y plantilla.</p>
                    <div class="text-sm text-gray-500">Completado: {{ $profile ? 'Si' : 'No' }}</div>
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('cv.profile.edit') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Editar perfil</a>
                        <a href="{{ route('cv.preview') }}" class="px-3 py-1 border rounded">Ver preview</a>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-5 space-y-2">
                    <p class="text-xs text-gray-500 uppercase">Paso 2</p>
                    <h3 class="text-lg font-semibold">Experiencia</h3>
                    <p class="text-sm text-gray-600">Registra cada puesto por separado.</p>
                    <div class="text-sm text-gray-500">Items: {{ $user->experiences_count }}</div>
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('cv.experiences.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.experiences.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-5 space-y-2">
                    <p class="text-xs text-gray-500 uppercase">Paso 3</p>
                    <h3 class="text-lg font-semibold">Educacion</h3>
                    <p class="text-sm text-gray-600">Carga titulos o cursos por item.</p>
                    <div class="text-sm text-gray-500">Items: {{ $user->educations_count }}</div>
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('cv.educations.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.educations.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-5 space-y-2">
                    <p class="text-xs text-gray-500 uppercase">Paso 4</p>
                    <h3 class="text-lg font-semibold">Certificados</h3>
                    <p class="text-sm text-gray-600">Certificaciones independientes.</p>
                    <div class="text-sm text-gray-500">Items: {{ $user->certificates_count }}</div>
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('cv.certificates.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.certificates.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-5 space-y-2">
                    <p class="text-xs text-gray-500 uppercase">Paso 5</p>
                    <h3 class="text-lg font-semibold">Habilidades</h3>
                    <p class="text-sm text-gray-600">Lista ordenable de skills.</p>
                    <div class="text-sm text-gray-500">Items: {{ $user->skills_count }}</div>
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('cv.skills.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Agregar</a>
                        <a href="{{ route('cv.skills.index') }}" class="px-3 py-1 border rounded">Ver lista</a>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-5 space-y-2">
                    <p class="text-xs text-gray-500 uppercase">Final</p>
                    <h3 class="text-lg font-semibold">Combinar y exportar</h3>
                    <p class="text-sm text-gray-600">Crea CVs dinamicos con los items que cargaste.</p>
                    <div class="flex gap-2 pt-2 flex-wrap">
                        <a href="{{ route('cvs.index') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Gestionar CVs</a>
                        <a href="{{ route('cvs.create') }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Nuevo CV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
