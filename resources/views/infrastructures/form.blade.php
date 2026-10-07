<div class="panel form-panel">
    <div class="form-grid">
        {{-- Section 1: Informations Générales --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin-bottom: 4px;">
            <span class="eyebrow">1. IDENTIFICATION GÉNÉRALE</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Informations de base</h2>
        </div>

        <label>
            Nom de l'infrastructure <span style="color: var(--coral);">*</span>
            <input type="text"
                   name="name"
                   id="input_infra_name"
                   class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name', $infrastructure->name ?? '') }}"
                   placeholder="Ex: Station de Pompage Ghdir El Golla"
                   data-label="Nom de l'infrastructure"
                   data-rules="required|min:3|letters_format">
            <span class="form-hint">Doit comporter au moins 3 caractères et contenir des lettres.</span>
            <div class="validation-feedback" data-field="name">
                @error('name')
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
            Code référence (généré automatiquement si vide)
            <input type="text"
                   name="reference_code"
                   id="input_infra_reference_code"
                   class="{{ $errors->has('reference_code') ? 'is-invalid' : '' }}"
                   value="{{ old('reference_code', $infrastructure->reference_code ?? '') }}"
                   placeholder="Ex: INF-TUN-001"
                   data-label="Code référence"
                   data-rules="reference_code">
            <span class="form-hint">Format standard : lettres, chiffres, tirets (ex: INF-2026-001)</span>
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
            Zone géographique d'implantation <span style="color: var(--coral);">*</span>
            <select name="zone_id"
                    id="input_infra_zone_id"
                    class="{{ $errors->has('zone_id') ? 'is-invalid' : '' }}"
                    data-label="Bassin / District"
                    data-rules="required">
                <option value="">Sélectionnez un bassin / district</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" @selected(old('zone_id', $infrastructure->zone_id ?? '') == $zone->id)>
                        {{ $zone->name }} (Risque : {{ $zone->risk_level }})
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" data-field="zone_id">
                @error('zone_id')
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
            Type d'ouvrage hydraulique <span style="color: var(--coral);">*</span>
            <select name="type"
                    id="input_infra_type"
                    class="{{ $errors->has('type') ? 'is-invalid' : '' }}"
                    data-label="Type d'ouvrage"
                    data-rules="required">
                <option value="">Sélectionnez un type</option>
                @foreach($types as $typeOpt)
                    <option value="{{ $typeOpt }}" @selected(old('type', $infrastructure->type ?? '') === $typeOpt)>
                        {{ $typeOpt }}
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

        <label class="form-full">
            Description détaillée & rôle dans le réseau
            <textarea name="description"
                      id="input_infra_description"
                      class="{{ $errors->has('description') ? 'is-invalid' : '' }}"
                      rows="3"
                      data-label="Description"
                      data-rules="max:3000"
                      placeholder="Précisez le rôle de l'ouvrage, les zones alimentées, ou des consignes d'exploitation...">{{ old('description', $infrastructure->description ?? '') }}</textarea>
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

        {{-- Section 2: Localisation --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin: 16px 0 4px;">
            <span class="eyebrow">2. GÉOLOCALISATION</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Emplacement sur le terrain</h2>
        </div>

        <label class="form-full">
            Adresse physique / Repère géographique
            <input type="text"
                   name="location"
                   id="input_infra_location"
                   class="{{ $errors->has('location') ? 'is-invalid' : '' }}"
                   value="{{ old('location', $infrastructure->location ?? '') }}"
                   data-label="Adresse / Emplacement"
                   data-rules="max:255"
                   placeholder="Ex: Route de Bizerte, Manouba / Grand Tunis">
            <div class="validation-feedback" data-field="location">
                @error('location')
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
            Latitude GPS (-90° à +90°)
            <input type="number"
                   step="any"
                   name="latitude"
                   id="input_infra_latitude"
                   class="{{ $errors->has('latitude') ? 'is-invalid' : '' }}"
                   value="{{ old('latitude', $infrastructure->latitude ?? '') }}"
                   placeholder="Ex: 36.793600"
                   data-label="Latitude GPS"
                   data-rules="number_between:-90:90">
            <div class="validation-feedback" data-field="latitude">
                @error('latitude')
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
            Longitude GPS (-180° à +180°)
            <input type="number"
                   step="any"
                   name="longitude"
                   id="input_infra_longitude"
                   class="{{ $errors->has('longitude') ? 'is-invalid' : '' }}"
                   value="{{ old('longitude', $infrastructure->longitude ?? '') }}"
                   placeholder="Ex: 10.057300"
                   data-label="Longitude GPS"
                   data-rules="number_between:-180:180">
            <div class="validation-feedback" data-field="longitude">
                @error('longitude')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        {{-- Section 3: Paramètres Techniques & État --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin: 16px 0 4px;">
            <span class="eyebrow">3. SPÉCIFICATIONS TECHNIQUES</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">État, statut & criticité</h2>
        </div>

        <label>
            Statut opérationnel <span style="color: var(--coral);">*</span>
            <select name="status"
                    id="input_infra_status"
                    class="{{ $errors->has('status') ? 'is-invalid' : '' }}"
                    data-label="Statut opérationnel"
                    data-rules="required">
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(old('status', $infrastructure->status ?? 'operational') === $key)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
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

        <label>
            État matériel (Condition physique) <span style="color: var(--coral);">*</span>
            <select name="condition"
                    id="input_infra_condition"
                    class="{{ $errors->has('condition') ? 'is-invalid' : '' }}"
                    data-label="État matériel"
                    data-rules="required">
                @foreach($conditions as $key => $label)
                    <option value="{{ $key }}" @selected(old('condition', $infrastructure->condition ?? 'good') === $key)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" data-field="condition">
                @error('condition')
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
            Niveau de criticité pour la population <span style="color: var(--coral);">*</span>
            <select name="criticality"
                    id="input_infra_criticality"
                    class="{{ $errors->has('criticality') ? 'is-invalid' : '' }}"
                    data-label="Niveau de criticité"
                    data-rules="required">
                @foreach($criticalities as $key => $label)
                    <option value="{{ $key }}" @selected(old('criticality', $infrastructure->criticality ?? 'medium') === $key)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" data-field="criticality">
                @error('criticality')
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
            Capacité nominale (Débit / Stockage)
            <input type="text"
                   name="capacity"
                   id="input_infra_capacity"
                   class="{{ $errors->has('capacity') ? 'is-invalid' : '' }}"
                   value="{{ old('capacity', $infrastructure->capacity ?? '') }}"
                   data-label="Capacité nominale"
                   data-rules="max:150"
                   placeholder="Ex: 650 000 m³/jour ou 580 000 000 m³">
            <div class="validation-feedback" data-field="capacity">
                @error('capacity')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>
        </label>

        {{-- Section 4: Cycle de Vie & Maintenance --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin: 16px 0 4px;">
            <span class="eyebrow">4. CALENDRIER D'EXPLOITATION</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Historique & cohérence des dates</h2>
        </div>

        <label>
            Date d'installation physique
            <input type="date"
                   id="input_installation_date"
                   name="installation_date"
                   max="{{ date('Y-m-d') }}"
                   class="{{ $errors->has('installation_date') ? 'is-invalid' : '' }}"
                   value="{{ old('installation_date', isset($infrastructure->installation_date) ? $infrastructure->installation_date->format('Y-m-d') : '') }}"
                   data-label="Date d'installation">
            <span class="form-hint">📅 Date passée ou actuelle (pose des équipements)</span>
            <div class="validation-feedback" data-field="installation_date">
                @error('installation_date')
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
            Date de mise en service officielle
            <input type="date"
                   id="input_commissioning_date"
                   name="commissioning_date"
                   class="{{ $errors->has('commissioning_date') ? 'is-invalid' : '' }}"
                   value="{{ old('commissioning_date', isset($infrastructure->commissioning_date) ? $infrastructure->commissioning_date->format('Y-m-d') : '') }}"
                   data-label="Date de mise en service">
            <span class="form-hint">📅 Postérieure ou égale à la date d'installation</span>
            <div class="validation-feedback" data-field="commissioning_date">
                @error('commissioning_date')
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
            Date de dernière maintenance réalisée
            <input type="date"
                   id="input_last_maintenance_date"
                   name="last_maintenance_date"
                   max="{{ date('Y-m-d') }}"
                   class="{{ $errors->has('last_maintenance_date') ? 'is-invalid' : '' }}"
                   value="{{ old('last_maintenance_date', isset($infrastructure->last_maintenance_date) ? $infrastructure->last_maintenance_date->format('Y-m-d') : '') }}"
                   data-label="Date de dernière maintenance">
            <span class="form-hint">📅 Passée ou aujourd'hui (dernière inspection technique)</span>
            <div class="validation-feedback" data-field="last_maintenance_date">
                @error('last_maintenance_date')
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
            Date de prochaine maintenance requise
            <input type="date"
                   id="input_next_maintenance_date"
                   name="next_maintenance_date"
                   class="{{ $errors->has('next_maintenance_date') ? 'is-invalid' : '' }}"
                   value="{{ old('next_maintenance_date', isset($infrastructure->next_maintenance_date) ? $infrastructure->next_maintenance_date->format('Y-m-d') : '') }}"
                   data-label="Prochaine date de maintenance">
            <span class="form-hint">📅 Doit être postérieure à la dernière maintenance</span>
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

        {{-- Section 5: Image --}}
        <div class="form-full" style="border-bottom: 1px solid var(--line); padding-bottom: 8px; margin: 16px 0 4px;">
            <span class="eyebrow">5. MÉDIA</span>
            <h2 style="font-size: 18px; margin: 4px 0 0;">Photo de l'ouvrage</h2>
        </div>

        <label class="form-full">
            Photo illustrative (JPG, PNG, WebP — max 4 Mo)
            <input type="file" name="image" id="input_infra_image" accept="image/*" class="{{ $errors->has('image') ? 'is-invalid' : '' }}">
            <span class="form-hint">Privilégiez une prise de vue nette de l'ouvrage ou des installations techniques.</span>
            <div class="validation-feedback" data-field="image">
                @error('image')
                    <span class="field-error-badge" role="alert">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            @if(isset($infrastructure) && $infrastructure->image_path)
                <div style="margin-top: 10px; display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('storage/' . $infrastructure->image_path) }}"
                         alt="{{ $infrastructure->name }}"
                         style="width: 120px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid var(--line);">
                    <span style="font-size: 11px; color: var(--muted);">Image actuellement enregistrée. Téléversez un nouveau fichier pour la remplacer.</span>
                </div>
            @endif
        </label>
    </div>

    <div class="form-actions">
        <a class="button button-outline" href="{{ isset($infrastructure) ? route('infrastructures.show', $infrastructure) : route('infrastructures.index') }}">
            Annuler
        </a>
        <button class="button" type="submit" id="btn_submit_infrastructure">
            {{ isset($infrastructure) ? 'Mettre à jour l’infrastructure' : 'Créer l’infrastructure' }}
        </button>
    </div>
</div>

{{-- Interactive date helper script & fallback validation --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const installInput = document.getElementById('input_installation_date');
    const commInput = document.getElementById('input_commissioning_date');
    const lastInput = document.getElementById('input_last_maintenance_date');
    const nextInput = document.getElementById('input_next_maintenance_date');

    function syncDates() {
        if (installInput && installInput.value) {
            if (commInput) commInput.min = installInput.value;
            if (lastInput) lastInput.min = installInput.value;
        }
        if (lastInput && lastInput.value) {
            if (nextInput) nextInput.min = lastInput.value;
        }
    }

    if (installInput) installInput.addEventListener('change', syncDates);
    if (lastInput) lastInput.addEventListener('change', syncDates);
    syncDates();
});
</script>
