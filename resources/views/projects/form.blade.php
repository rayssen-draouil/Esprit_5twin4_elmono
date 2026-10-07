@csrf
<div class="form-grid">
    <label>Nom du projet
        <input name="name" value="{{ old('name', $project->name ?? '') }}" required>
        @error('name')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Type
        <input name="type" value="{{ old('type', $project->type ?? '') }}" required>
        @error('type')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label class="form-full">Description
        <textarea name="description" rows="5">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Budget
        <input type="number" name="budget" min="0" step="0.01" value="{{ old('budget', $project->budget ?? '') }}" required>
        @error('budget')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Statut
        <select name="status" required>
            @foreach(['planned' => 'Planifié', 'in_progress' => 'En cours', 'completed' => 'Terminé', 'cancelled' => 'Annulé'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $project->status ?? 'planned') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Date de début
        <input type="date" name="start_date" value="{{ old('start_date', isset($project?->start_date) ? $project->start_date?->format('Y-m-d') : '') }}">
        @error('start_date')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Date de fin
        <input type="date" name="end_date" value="{{ old('end_date', isset($project?->end_date) ? $project->end_date?->format('Y-m-d') : '') }}">
        @error('end_date')<small class="field-error">{{ $message }}</small>@enderror
    </label>
    <label>Progression (%)
        <input type="number" name="progress" min="0" max="100" value="{{ old('progress', $project->progress ?? 0) }}" required>
        @error('progress')<small class="field-error">{{ $message }}</small>@enderror
    </label>
</div>
<div class="form-actions"><a class="button button-ghost" href="{{ route('projects.index') }}">Annuler</a><button class="button" type="submit">{{ $submitLabel }}</button></div>
