@csrf
<div class="form-grid">
    <label>Nom complet
        <input name="name" value="{{ old('name', $technician->name ?? '') }}" required>
        @error('name')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Email
        <input type="email" name="email" value="{{ old('email', $technician->email ?? '') }}">
        @error('email')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Téléphone
        <input type="tel" name="phone" value="{{ old('phone', $technician->phone ?? '') }}">
        @error('phone')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Spécialité
        <input name="speciality" value="{{ old('speciality', $technician->speciality ?? '') }}">
        @error('speciality')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Statut
        <select name="status" required>
            @foreach(['available' => 'Disponible', 'busy' => 'Occupé', 'inactive' => 'Inactif'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $technician->status ?? 'available') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<small class="field-error">{{ $message }}</small>@enderror
    </label>
</div>
<div class="form-actions"><a class="button button-ghost" href="{{ route('techniciens.index') }}">Annuler</a><button class="button" type="submit">{{ $submitLabel }}</button></div>