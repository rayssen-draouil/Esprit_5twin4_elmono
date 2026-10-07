@csrf
@if($incidents->isEmpty())
    <p class="flash-message flash-warning">Aucun incident n’est disponible pour planifier une intervention. Confirmez d’abord un signalement dans <a href="{{ route('back.incidents') }}">la gestion des incidents</a>.</p>
@endif
<div class="form-grid">
    <label class="form-full">Incident
        <select name="incident_id" required>
            <option value="">Sélectionner un incident</option>
            @foreach($incidents as $incidentOption)
                <option value="{{ $incidentOption->id }}" @selected((string) old('incident_id', $intervention->incident_id ?? '') === (string) $incidentOption->id)>#{{ $incidentOption->id }} · {{ $incidentOption->type }} · {{ $incidentOption->location ?: 'Lieu non précisé' }}</option>
            @endforeach
        </select>
        @error('incident_id')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Technicien
        <select name="technician_id">
            <option value="">Non affecté</option>
            @foreach($technicians as $technicianOption)
                <option value="{{ $technicianOption->id }}" @selected((string) old('technician_id', $intervention->technician_id ?? '') === (string) $technicianOption->id)>{{ $technicianOption->name }} · {{ $technicianOption->speciality ?: 'Sans spécialité' }}</option>
            @endforeach
        </select>
        @error('technician_id')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Équipe
        <input name="team" value="{{ old('team', $intervention->team ?? '') }}" required>
        @error('team')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Date prévue
        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', isset($intervention?->scheduled_at) ? $intervention->scheduled_at?->format('Y-m-d\TH:i') : '') }}" required>
        @error('scheduled_at')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Statut
        <select name="status" required>
            @foreach(['planned' => 'Planifiée', 'in_progress' => 'En cours', 'completed' => 'Terminée', 'cancelled' => 'Annulée'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $intervention->status ?? 'planned') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Date de début
        <input type="datetime-local" name="started_at" value="{{ old('started_at', isset($intervention?->started_at) ? $intervention->started_at?->format('Y-m-d\TH:i') : '') }}">
        @error('started_at')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Date de fin
        <input type="datetime-local" name="completed_at" value="{{ old('completed_at', isset($intervention?->completed_at) ? $intervention->completed_at?->format('Y-m-d\TH:i') : '') }}">
        @error('completed_at')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label class="form-full">Résultat / compte rendu
        <textarea name="result" rows="4">{{ old('result', $intervention->result ?? '') }}</textarea>
        @error('result')<small class="field-error">{{ $message }}</small>@enderror
    </label>
</div>
<div class="form-actions"><a class="button button-ghost" href="{{ route('interventions.index') }}">Annuler</a><button class="button" type="submit">{{ $submitLabel }}</button></div>