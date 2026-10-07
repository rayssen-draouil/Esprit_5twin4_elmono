<div class="panel form-panel">
    <div class="form-grid">
        {{-- Section 1: Association & Type --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin-bottom: 4px;">
            <span class="eyebrow">1. INFRASTRUCTURE & CARACTÉRISTIQUES</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Cible de l'intervention</h2>
        </div>

        <label class="form-full">
            Infrastructure concernée <span style="color: var(--coral);">*</span>
            <select name="infrastructure_id"
                    id="select_infrastructure_id"
                    class="{{ $errors->has('infrastructure_id') ? 'is-invalid' : '' }}"
                    data-label="Infrastructure"
                    data-rules="required">
                <option value="">Sélectionnez l'ouvrage cible</option>
                @foreach($infrastructures as $infra)
                    <option value="{{ $infra->id }}"
                            @selected(old('infrastructure_id', $maintenance->infrastructure_id ?? ($selectedInfrastructureId ?? '')) == $infra->id)>
                        {{ $infra->name }} ({{ $infra->reference_code }})
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" data-field="infrastructure_id">
                @error('infrastructure_id')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Code référence de l'opération
            <input type="text"
                   name="reference_code"
                   id="input_maintenance_reference_code"
                   class="{{ $errors->has('reference_code') ? 'is-invalid' : '' }}"
                   value="{{ old('reference_code', $maintenance->reference_code ?? '') }}"
                   placeholder="Ex: MNT-2026-001 (auto si vide)"
                   data-label="Code référence"
                   data-rules="reference_code">
            <span class="form-hint">Exemple : MNT-2026-001 (généré automatiquement si vide)</span>
            <div class="validation-feedback" data-field="reference_code">
                @error('reference_code')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Type de maintenance <span style="color: var(--coral);">*</span>
            <select name="type"
                    id="select_maintenance_type"
                    class="{{ $errors->has('type') ? 'is-invalid' : '' }}"
                    data-label="Type de maintenance"
                    data-rules="required">
                @foreach($types as $t)
                    <option value="{{ $t }}" @selected(old('type', $maintenance->type ?? 'Préventive') === $t)>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" data-field="type">
                @error('type')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Priorité opérationnelle <span style="color: var(--coral);">*</span>
            <select name="priority"
                    id="select_maintenance_priority"
                    class="{{ $errors->has('priority') ? 'is-invalid' : '' }}"
                    data-label="Priorité opérationnelle"
                    data-rules="required">
                @foreach($priorities as $pval => $plabel)
                    <option value="{{ $pval }}" @selected(old('priority', $maintenance->priority ?? 'medium') === $pval)>
                        {{ $plabel }}
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" data-field="priority">
                @error('priority')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Statut actuel <span style="color: var(--coral);">*</span>
            <select name="status"
                    id="select_status"
                    class="{{ $errors->has('status') ? 'is-invalid' : '' }}"
                    data-label="Statut actuel"
                    data-rules="required">
                @foreach($statuses as $sval => $slabel)
                    <option value="{{ $sval }}" @selected(old('status', $maintenance->status ?? 'planned') === $sval)>
                        {{ $slabel }}
                    </option>
                @endforeach
            </select>
            <span class="form-hint">Si « Terminée », le compte-rendu technique est obligatoire.</span>
            <div class="validation-feedback" data-field="status">
                @error('status')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        {{-- Section 2: Affectation --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin: 16px 0 4px;">
            <span class="eyebrow">2. RESSOURCES HUMAINES</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Affectation technique</h2>
        </div>

        <label>
            Technicien responsable
            <select name="technician_id" id="select_technician_id" class="{{ $errors->has('technician_id') ? 'is-invalid' : '' }}">
                <option value="">Aucun technicien assigné</option>
                @foreach($technicians as $tech)
                    <option value="{{ $tech->id }}" @selected(old('technician_id', $maintenance->technician_id ?? '') == $tech->id)>
                        {{ $tech->name }} @if($tech->speciality) ({{ $tech->speciality }}) @endif
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" data-field="technician_id">
                @error('technician_id')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Équipe ou Entreprise prestataire
            <input type="text"
                   name="team"
                   id="input_team"
                   class="{{ $errors->has('team') ? 'is-invalid' : '' }}"
                   value="{{ old('team', $maintenance->team ?? '') }}"
                   placeholder="Ex: Équipe Électromécanique SONEDE Tunis"
                   data-label="Équipe ou Entreprise prestataire"
                   data-rules="letters_only">
            <span class="form-hint">Doit contenir uniquement des lettres, espaces ou tirets (aucun chiffre).</span>
            <div class="validation-feedback" data-field="team">
                @error('team')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        {{-- Section 3: Calendrier --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin: 16px 0 4px;">
            <span class="eyebrow">3. PLANNING & HORODATAGE</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Dates d'exécution & chronologie</h2>
        </div>

        <label>
            Date et heure planifiées <span style="color: var(--coral);">*</span>
            <input type="datetime-local"
                   id="input_scheduled_at"
                   name="scheduled_at"
                   class="{{ $errors->has('scheduled_at') ? 'is-invalid' : '' }}"
                   value="{{ old('scheduled_at', isset($maintenance->scheduled_at) ? $maintenance->scheduled_at->format('Y-m-d\TH:i') : now()->addDays(3)->format('Y-m-d\T09:00')) }}"
                   data-label="Date et heure planifiées"
                   data-rules="required">
            <span class="form-hint">📅 Date prévisionnelle (dans le futur si statut « Planifiée »)</span>
            <div class="validation-feedback" data-field="scheduled_at">
                @error('scheduled_at')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Date de début réel sur site
            <input type="datetime-local"
                   id="input_started_at"
                   name="started_at"
                   max="{{ date('Y-m-d\TH:i') }}"
                   class="{{ $errors->has('started_at') ? 'is-invalid' : '' }}"
                   value="{{ old('started_at', isset($maintenance->started_at) ? $maintenance->started_at->format('Y-m-d\TH:i') : '') }}"
                   data-label="Date de début réel">
            <span class="form-hint">⏱ Début réel (ne peut pas être dans le futur)</span>
            <div class="validation-feedback" data-field="started_at">
                @error('started_at')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Date d'achèvement effectif
            <input type="datetime-local"
                   id="input_completed_at"
                   name="completed_at"
                   max="{{ date('Y-m-d\TH:i') }}"
                   class="{{ $errors->has('completed_at') ? 'is-invalid' : '' }}"
                   value="{{ old('completed_at', isset($maintenance->completed_at) ? $maintenance->completed_at->format('Y-m-d\TH:i') : '') }}"
                   data-label="Date d'achèvement">
            <span class="form-hint">🏁 Clôture effective (postérieure au début d'intervention)</span>
            <div class="validation-feedback" data-field="completed_at">
                @error('completed_at')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Prochaine échéance recommandée suite aux travaux
            <input type="date"
                   id="input_next_date"
                   name="next_maintenance_date"
                   min="{{ date('Y-m-d') }}"
                   class="{{ $errors->has('next_maintenance_date') ? 'is-invalid' : '' }}"
                   value="{{ old('next_maintenance_date', isset($maintenance->next_maintenance_date) ? $maintenance->next_maintenance_date->format('Y-m-d') : '') }}"
                   data-label="Prochaine échéance">
            <span class="form-hint">🔄 Doit être planifiée dans le futur</span>
            <div class="validation-feedback" data-field="next_maintenance_date">
                @error('next_maintenance_date')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        {{-- Section 4: Budget & Rapport --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin: 16px 0 4px;">
            <span class="eyebrow">4. ASPECTS BUDGÉTAIRES & TRAVAUX</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Consommation & compte-rendu</h2>
        </div>

        <label>
            Coût estimé ou facturé (DT ou €)
            <input type="number"
                   step="0.01"
                   min="0"
                   name="cost"
                   id="input_maintenance_cost"
                   class="{{ $errors->has('cost') ? 'is-invalid' : '' }}"
                   value="{{ old('cost', $maintenance->cost ?? '') }}"
                   placeholder="Ex: 1450.00"
                   data-label="Coût financier"
                   data-rules="positive_number">
            <span class="form-hint">Montant numérique positif ou nul (ex: 2800.50)</span>
            <div class="validation-feedback" data-field="cost">
                @error('cost')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label>
            Durée d'intervention (heures)
            <input type="number"
                   step="0.1"
                   min="0.1"
                   max="500"
                   name="duration_hours"
                   id="input_maintenance_duration"
                   class="{{ $errors->has('duration_hours') ? 'is-invalid' : '' }}"
                   value="{{ old('duration_hours', $maintenance->duration_hours ?? '') }}"
                   placeholder="Ex: 3.5"
                   data-label="Durée d'intervention"
                   data-rules="number_between:0.1:500">
            <span class="form-hint">Durée cumulée (ex: 4.5 pour 4 heures et 30 minutes)</span>
            <div class="validation-feedback" data-field="duration_hours">
                @error('duration_hours')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label class="form-full">
            Description des opérations prévues
            <textarea name="description"
                      id="input_maintenance_description"
                      class="{{ $errors->has('description') ? 'is-invalid' : '' }}"
                      rows="3"
                      data-label="Description"
                      data-rules="max:3000"
                      placeholder="Détaillez les points de contrôle, pièces à vérifier ou motifs d'intervention...">{{ old('description', $maintenance->description ?? '') }}</textarea>
            <div class="validation-feedback" data-field="description">
                @error('description')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        <label class="form-full">
            Compte-rendu technique & constatations finalisées
            <textarea name="result"
                      id="input_result"
                      class="{{ $errors->has('result') ? 'is-invalid' : '' }}"
                      rows="3"
                      data-label="Compte-rendu technique"
                      data-rules="max:3000"
                      placeholder="Renseignez le bilan des opérations, pièces remplacées, anomalies résolues...">{{ old('result', $maintenance->result ?? '') }}</textarea>
            <span class="form-hint" id="result_hint">Obligatoire si le statut est « Terminée ».</span>
            <div class="validation-feedback" data-field="result">
                @error('result')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>
    </div>

    <div class="form-actions">
        <a class="button button-outline" href="{{ isset($maintenance) ? route('maintenances.show', $maintenance) : route('maintenances.index') }}">
            Annuler
        </a>
        <button class="button" type="submit" id="btn_submit_maintenance">
            {{ isset($maintenance) ? 'Enregistrer les modifications' : 'Créer l’opération de maintenance' }}
        </button>
    </div>
</div>

{{-- Interactive maintenance coherence helper --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('select_status');
    const resultInput = document.getElementById('input_result');
    const resultHint = document.getElementById('result_hint');
    const startedInput = document.getElementById('input_started_at');
    const completedInput = document.getElementById('input_completed_at');
    const scheduledInput = document.getElementById('input_scheduled_at');
    const nextDateInput = document.getElementById('input_next_date');

    function checkStatus() {
        if (statusSelect && statusSelect.value === 'completed') {
            if (resultHint) {
                resultHint.style.color = 'var(--coral)';
                resultHint.style.fontWeight = '700';
                resultHint.textContent = '⚠ Attention : le compte-rendu technique est obligatoire pour enregistrer une maintenance terminée.';
            }
            if (resultInput) {
                resultInput.dataset.rules = 'required|max:3000';
            }
        } else {
            if (resultHint) {
                resultHint.style.color = 'var(--muted)';
                resultHint.style.fontWeight = 'normal';
                resultHint.textContent = 'Obligatoire si le statut est « Terminée ».';
            }
            if (resultInput) {
                resultInput.dataset.rules = 'max:3000';
            }
        }
    }

    function syncTimestamps() {
        if (startedInput && startedInput.value && completedInput) {
            completedInput.min = startedInput.value;
        }
        if (scheduledInput && scheduledInput.value && nextDateInput) {
            const schedDate = scheduledInput.value.split('T')[0];
            if (schedDate) {
                nextDateInput.min = schedDate;
            }
        }
    }

    if (statusSelect) statusSelect.addEventListener('change', checkStatus);
    if (startedInput) startedInput.addEventListener('change', syncTimestamps);
    if (scheduledInput) scheduledInput.addEventListener('change', syncTimestamps);

    checkStatus();
    syncTimestamps();
});
</script>
