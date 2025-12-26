@php($exp = $exp ?? null)
<div>
    <label class="block text-sm font-medium">Titulo</label>
    <input name="title" value="{{ old('title', optional($exp)->title) }}" class="mt-1 w-full border rounded p-2" required />
    @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Empresa</label>
    <input name="company" value="{{ old('company', optional($exp)->company) }}" class="mt-1 w-full border rounded p-2" />
    @error('company') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Inicio</label>
        <input type="date" name="start_date" id="experience-start-date" value="{{ old('start_date', optional(optional($exp)->start_date)->format('Y-m-d')) }}" class="mt-1 w-full border rounded p-2" required />
        @error('start_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Fin</label>
        <input type="date" name="end_date" id="experience-end-date" value="{{ old('end_date', optional(optional($exp)->end_date)->format('Y-m-d')) }}" class="mt-1 w-full border rounded p-2" />
        @error('end_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
<div class="flex items-center gap-2">
    <input type="checkbox" name="current" id="experience-current" value="1" {{ old('current', optional($exp)->current) ? 'checked' : '' }} />
    <span>Trabajo aqui actualmente</span>
    @error('current') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Ubicacion</label>
    <input name="location" value="{{ old('location', optional($exp)->location) }}" class="mt-1 w-full border rounded p-2" />
    @error('location') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Descripcion</label>
    <textarea name="description" rows="4" class="mt-1 w-full border rounded p-2">{{ old('description', optional($exp)->description) }}</textarea>
    @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var checkbox = document.getElementById('experience-current');
    var endDate = document.getElementById('experience-end-date');
    if (!checkbox || !endDate) return;
    var toggleEndDate = function () {
        if (checkbox.checked) {
            endDate.value = '';
            endDate.setAttribute('disabled', 'disabled');
        } else {
            endDate.removeAttribute('disabled');
        }
    };
    checkbox.addEventListener('change', toggleEndDate);
    toggleEndDate();
});
</script>
