@php($skillModel = $skillModel ?? null)
<div>
    <label class="block text-sm font-medium">Nombre</label>
    <input name="name" value="{{ old('name', optional($skillModel)->name) }}" class="mt-1 w-full border rounded p-2" required />
    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Nivel</label>
        @php($lvl = old('level', optional($skillModel)->level))
        <select name="level" class="mt-1 w-full border rounded p-2">
            <option value="">(Sin nivel)</option>
            <option value="Basico" {{ $lvl === 'Basico' ? 'selected' : '' }}>Basico</option>
            <option value="Intermedio" {{ $lvl === 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
            <option value="Avanzado" {{ $lvl === 'Avanzado' ? 'selected' : '' }}>Avanzado</option>
        </select>
        @error('level') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Orden</label>
        <input type="number" name="order" value="{{ old('order', optional($skillModel)->order ?? 0) }}" class="mt-1 w-full border rounded p-2" />
        @error('order') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
