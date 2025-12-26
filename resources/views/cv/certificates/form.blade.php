@php($cert = $cert ?? null)
<div>
    <label class="block text-sm font-medium">Nombre</label>
    <input name="name" value="{{ old('name', optional($cert)->name) }}" class="mt-1 w-full border rounded p-2" required />
    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm font-medium">Entidad emisora</label>
    <input name="issuer" value="{{ old('issuer', optional($cert)->issuer) }}" class="mt-1 w-full border rounded p-2" />
    @error('issuer') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Fecha</label>
        <input type="date" name="date" value="{{ old('date', optional(optional($cert)->date)->format('Y-m-d')) }}" class="mt-1 w-full border rounded p-2" />
        @error('date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">ID credencial</label>
        <input name="credential_id" value="{{ old('credential_id', optional($cert)->credential_id) }}" class="mt-1 w-full border rounded p-2" />
        @error('credential_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
<div>
    <label class="block text-sm font-medium">URL credencial</label>
    <input name="credential_url" value="{{ old('credential_url', optional($cert)->credential_url) }}" class="mt-1 w-full border rounded p-2" />
    @error('credential_url') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>
