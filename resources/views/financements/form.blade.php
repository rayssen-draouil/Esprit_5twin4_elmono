@csrf
<div class="form-grid">
    <label class="form-full">Projet
        <select name="project_id" required>
            <option value="">Sélectionner un projet</option>
            @foreach($projects as $projectOption)
                <option value="{{ $projectOption->id }}" @selected((string) old('project_id', $selectedProject ?? $financement->project_id ?? '') === (string) $projectOption->id)>{{ $projectOption->name }}</option>
            @endforeach
        </select>
        @error('project_id')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Source
        <input name="source" value="{{ old('source', $financement->source ?? '') }}" required>
        @error('source')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Montant
        <input type="number" name="amount" min="0" step="0.01" value="{{ old('amount', $financement->amount ?? '') }}" required>
        @error('amount')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Statut
        <select name="status" required>
            @foreach(['planned' => 'Planifié', 'funded' => 'Financé', 'pending' => 'En attente', 'cancelled' => 'Annulé'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $financement->status ?? 'planned') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Date de financement
        <input type="date" name="funded_at" value="{{ old('funded_at', isset($financement?->funded_at) ? $financement->funded_at?->format('Y-m-d') : '') }}">
        @error('funded_at')<small class="field-error">{{ $message }}</small>@enderror
    </label>
</div>
<div class="form-actions"><a class="button button-ghost" href="{{ route('financements.index') }}">Annuler</a><button class="button" type="submit">{{ $submitLabel }}</button></div>
