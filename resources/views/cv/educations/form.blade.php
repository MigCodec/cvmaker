@php($edu = $edu ?? null)
<div>
    <label class="block text-sm font-medium">Titulo o grado</label>
    <input name="degree" value="{{ old('degree', optional($edu)->degree) }}" class="mt-1 w-full border rounded p-2" required />
    @error('degree') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Institucion</label>
    <input name="institution" value="{{ old('institution', optional($edu)->institution) }}" class="mt-1 w-full border rounded p-2" required />
    @error('institution') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Area o especialidad</label>
    <input name="field" value="{{ old('field', optional($edu)->field) }}" class="mt-1 w-full border rounded p-2" />
    @error('field') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Inicio</label>
        <input type="date" name="start_date" id="education-start-date" value="{{ old('start_date', optional(optional($edu)->start_date)->format('Y-m-d')) }}" class="mt-1 w-full border rounded p-2" required />
        @error('start_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Fin</label>
        <input type="date" name="end_date" id="education-end-date" value="{{ old('end_date', optional(optional($edu)->end_date)->format('Y-m-d')) }}" class="mt-1 w-full border rounded p-2" />
        @error('end_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
<div class="flex items-center gap-2">
    <input type="checkbox" name="current" id="education-current" value="1" {{ old('current', optional($edu)->current) ? 'checked' : '' }} />
    <span>Cursando actualmente</span>
    @error('current') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Ubicacion</label>
    <input name="location" value="{{ old('location', optional($edu)->location) }}" class="mt-1 w-full border rounded p-2" />
    @error('location') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Descripcion</label>
    <textarea name="description" rows="4" class="mt-1 w-full border rounded p-2">{{ old('description', optional($edu)->description) }}</textarea>
    @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var checkbox = document.getElementById('education-current');
    var endDate = document.getElementById('education-end-date');
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
