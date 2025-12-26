@php($lang = $lang ?? null)
<div>
    <label class="block text-sm font-medium">Idioma</label>
    <input name="name" value="{{ old('name', optional($lang)->name) }}" class="mt-1 w-full border rounded p-2" required />
    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Nivel</label>
    <input name="level" value="{{ old('level', optional($lang)->level) }}" class="mt-1 w-full border rounded p-2" placeholder="Ej: B2, C1, Nativo" />
    @error('level') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Orden</label>
    <input type="number" name="order" value="{{ old('order', optional($lang)->order) }}" class="mt-1 w-full border rounded p-2" min="0" />
    @error('order') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
