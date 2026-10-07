@extends('layouts.front')

@section('content')
{{-- 1. Hero Section --}}
<section class="page-hero compact">
    <div style="margin-bottom: 12px; font-size: 13px; color: var(--muted);">
        <a href="{{ route('home') }}" style="color: var(--muted);">Accueil</a>
        <span style="margin: 0 8px;">/</span>
        <a href="{{ route('front.infrastructures.index') }}" style="color: var(--muted);">Infrastructures</a>
        <span style="margin: 0 8px;">/</span>
        <strong style="color: var(--deep);">{{ $infrastructure->name }}</strong>
    </div>

    <span class="eyebrow">{{ $infrastructureCode }} · {{ $infrastructure->type }}</span>
    <h1>{{ $infrastructure->name }}</h1>
    <p>
        {{ $infrastructure->zone?->name ?? 'France' }}
        @if($infrastructure->location) · {{ $infrastructure->location }} @endif
    </p>

    <div style="margin-top: 18px;">
        <span class="badge {{ $infrastructure->status_badge_class }}" style="font-size: 12px; padding: 5px 12px;">
            ● {{ $infrastructure->status_label }}
        </span>
    </div>
</section>

{{-- 2. Details & Public Information --}}
<section class="section two-col">
    {{-- Left Column: Description, Specs & Maintenance History --}}
    <div>
        <span class="eyebrow">FONCTIONNEMENT & RÔLE</span>
        <h2 style="margin: 12px 0 16px;">Sécurité hydrique & surveillance continue.</h2>

        <p class="lead" style="margin-bottom: 24px;">
            {{ $infrastructure->description ?: 'Cet ouvrage d’intérêt général fait partie du maillage régional AquaSecure, assurant la régulation, le contrôle qualité et la distribution continue de la ressource en eau.' }}
        </p>

        {{-- Technical Specs Grid --}}
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; background: #fff; padding: 22px; border-radius: 8px; border: 1px solid var(--line); margin-bottom: 35px;">
            <div>
                <span style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Capacité nominale</span>
                <strong style="display: block; font-size: 15px; color: var(--ink); margin-top: 3px;">
                    {{ $infrastructure->capacity ?: 'Régulation standard' }}
                </strong>
            </div>
            <div>
                <span style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Mise en service</span>
                <strong style="display: block; font-size: 15px; color: var(--ink); margin-top: 3px;">
                    {{ $infrastructure->commissioning_date ? $infrastructure->commissioning_date->format('d/m/Y') : ($infrastructure->installation_date ? $infrastructure->installation_date->format('d/m/Y') : 'Historique certifié') }}
                </strong>
            </div>
            <div>
                <span style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Bassin de rattachement</span>
                <strong style="display: block; font-size: 15px; color: var(--ink); margin-top: 3px;">
                    {{ $infrastructure->zone?->name ?? 'Territoire national' }}
                </strong>
            </div>
            <div>
                <span style="font-size: 11px; color: var(--muted); text-transform: uppercase;">Condition matérielle</span>
                <strong style="display: block; font-size: 15px; color: var(--ink); margin-top: 3px;">
                    {{ $infrastructure->condition_label }}
                </strong>
            </div>
        </div>

        {{-- Public Maintenance History --}}
        <div>
            <span class="eyebrow">TRANSPARENCE DES CONTRÔLES</span>
            <h3 style="font-size: 24px; margin: 8px 0 16px;">Historique & Traçabilité des maintenances</h3>
            <p style="color: var(--muted); font-size: 14px; margin-bottom: 20px;">
                Conformément aux engagements de service public, retrouvez les opérations de contrôle préventif et d'entretien effectuées sur cet ouvrage.
            </p>

            <div class="lifecycle-timeline">
                @forelse($infrastructure->maintenances as $m)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $m->status === 'completed' ? 'done' : '' }}">
                            {{ $m->status === 'completed' ? '✓' : '⏱' }}
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-content-head">
                                <div>
                                    <h4 style="margin: 0; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                                        {{ $m->type }}
                                        <span class="badge {{ $m->status_badge_class }}">
                                            {{ $m->display_status_label }}
                                        </span>
                                    </h4>
                                    <small style="color: var(--muted); display: block; margin-top: 2px;">
                                        Réf. {{ $m->reference_code }}
                                    </small>
                                </div>
                                <span style="font-size: 12px; color: var(--muted);">
                                    {{ $m->scheduled_at ? $m->scheduled_at->format('M Y') : 'Date planifiée' }}
                                </span>
                            </div>

                            <p style="margin: 6px 0 0; font-size: 13px; color: var(--ink);">
                                {{ $m->description }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="timeline-item">
                        <div class="timeline-marker">○</div>
                        <div class="timeline-content">
                            <p style="margin: 0; color: var(--muted); font-size: 13px;">
                                Aucune opération de maintenance archivée pour cette infrastructure.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Column: Live Status & Citizen CTA --}}
    <div>
        {{-- Status Panel --}}
        <div class="panel" style="margin-bottom: 24px;">
            <span class="eyebrow">INDICATEURS DE FONCTIONNEMENT</span>
            <h3 style="margin: 6px 0 20px; font-size: 20px;">État du site</h3>

            <div style="margin-bottom: 18px;">
                <span style="font-size: 12px; color: var(--muted);">Statut opérationnel</span>
                <div style="font-size: 16px; font-weight: 700; color: var(--ink); margin-top: 3px;">
                    <span class="badge {{ $infrastructure->status_badge_class }}">
                        {{ $infrastructure->status_label }}
                    </span>
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 6px;">
                    <span style="color: var(--muted);">Santé globale de l'ouvrage</span>
                    <strong style="color: var(--ink);">{{ $infrastructure->health_score }}% ({{ $infrastructure->health_label }})</strong>
                </div>
                <div class="progress" style="margin: 0;">
                    <i style="width: {{ $infrastructure->health_score }}%; background: {{ $infrastructure->health_score >= 70 ? 'var(--aqua)' : ($infrastructure->health_score >= 50 ? '#aa721c' : 'var(--coral)') }};"></i>
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <span style="font-size: 12px; color: var(--muted);">Dernière vérification validée</span>
                <strong style="display: block; font-size: 14px; color: var(--ink); margin-top: 3px;">
                    {{ $infrastructure->last_maintenance_date ? $infrastructure->last_maintenance_date->format('d/m/Y') : 'Contrôle initial' }}
                </strong>
            </div>

            @if($infrastructure->next_maintenance_date)
                <div style="margin-bottom: 18px;">
                    <span style="font-size: 12px; color: var(--muted);">Prochaine inspection prévue</span>
                    <strong style="display: block; font-size: 14px; color: var(--deep); margin-top: 3px;">
                        {{ $infrastructure->next_maintenance_date->format('d/m/Y') }}
                    </strong>
                </div>
            @endif

            <div style="border-top: 1px solid var(--line); padding-top: 15px; font-size: 12px; color: var(--muted);">
                Télémétrie actualisée en continu par les capteurs AquaSecure.
            </div>
        </div>

        {{-- Photo if present --}}
        @if($infrastructure->image_path)
            <div class="panel" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
                <img src="{{ asset('storage/' . $infrastructure->image_path) }}"
                     alt="{{ $infrastructure->name }}"
                     style="width: 100%; height: 220px; object-fit: cover; display: block;">
            </div>
        @endif

        {{-- Citizen Malfunction Report Form --}}
        <div class="panel" style="background: #edf6f4; border-color: #cbe3dd;" id="signaler-panne">
            <span class="eyebrow">PARTICIPATION CITOYENNE</span>
            <h4 style="margin: 4px 0 8px; font-size: 18px; color: var(--deep);">Signaler un dysfonctionnement</h4>
            <p style="font-size: 12px; color: var(--muted); margin-bottom: 16px;">
                Vous constatez une fuite, une coupure anormale, un bruit suspect de pompe ou une anomalie sur cet ouvrage ? Transmettez directement votre alerte aux équipes techniques pour déclencher une maintenance.
            </p>

            @if(session('success'))
                <div style="background: #e6f6ef; border: 1px solid #7cd1af; border-radius: 8px; padding: 14px 16px; margin-bottom: 16px; color: #177853; display: flex; align-items: flex-start; gap: 10px;">
                    <span style="font-size: 18px; font-weight: 800; line-height: 1;">✓</span>
                    <div>
                        <strong style="display: block; font-size: 13px; margin-bottom: 3px;">Signalement transmis avec succès</strong>
                        <div style="font-size: 12px; line-height: 1.5;">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fde9e7; border: 1px solid #f29c94; border-radius: 8px; padding: 14px 16px; margin-bottom: 16px; color: #a8473d; display: flex; align-items: flex-start; gap: 10px;">
                    <span style="font-size: 18px; font-weight: 800; line-height: 1;">✕</span>
                    <div style="flex: 1;">
                        <strong style="display: block; font-size: 13px; margin-bottom: 4px;">Vérification requise ({{ $errors->count() }} erreur{{ $errors->count() > 1 ? 's' : '' }}) :</strong>
                        <ul style="margin: 0; padding-left: 18px; font-size: 12px; line-height: 1.5;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('front.infrastructures.report', $infrastructureCode) }}" novalidate id="form_report_malfunction" class="validated-form">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 4px;">
                            Type d'anomalie constatée <span style="color: var(--coral);">*</span>
                        </label>
                        <select name="malfunction_type"
                                id="report_malfunction_type"
                                data-label="Type d'anomalie"
                                data-rules="required"
                                style="width: 100%; border: 1px solid {{ $errors->has('malfunction_type') ? 'var(--coral)' : 'var(--line)' }}; border-radius: 5px; padding: 9px 12px; font: inherit; font-size: 12px; background: #fff;">
                            <option value="">Sélectionnez une anomalie</option>
                            <option value="Fuite d'eau apparente ou éclatement de conduite" @selected(old('malfunction_type') === "Fuite d'eau apparente ou éclatement de conduite")>💧 Fuite d'eau apparente sur conduite</option>
                            <option value="Arrêt / Panne de motopompe ou surpresseur" @selected(old('malfunction_type') === "Arrêt / Panne de motopompe ou surpresseur")>⚙ Arrêt ou bruit anormal de pompe</option>
                            <option value="Chute anormale de pression au robinet" @selected(old('malfunction_type') === "Chute anormale de pression au robinet")>📉 Chute brutale de pression dans le secteur</option>
                            <option value="Eau trouble / coloration suspecte" @selected(old('malfunction_type') === "Eau trouble / coloration suspecte")>🧪 Eau trouble ou coloration suspecte</option>
                            <option value="Dégradation matérielle / Clôture ou vanne endommagée" @selected(old('malfunction_type') === "Dégradation matérielle / Clôture ou vanne endommagée")>🚧 Dégradation physique de l'ouvrage</option>
                            <option value="Autre anomalie technique" @selected(old('malfunction_type') === "Autre anomalie technique")>⚠ Autre problème technique</option>
                        </select>
                        <div class="validation-feedback" data-field="malfunction_type">
                            @error('malfunction_type')
                                <span class="field-error-badge" role="alert">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span>{{ $message }}</span>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 4px;">
                            Gravité constatée <span style="color: var(--coral);">*</span>
                        </label>
                        <select name="priority"
                                id="report_priority"
                                data-label="Gravité"
                                data-rules="required"
                                style="width: 100%; border: 1px solid {{ $errors->has('priority') ? 'var(--coral)' : 'var(--line)' }}; border-radius: 5px; padding: 9px 12px; font: inherit; font-size: 12px; background: #fff;">
                            <option value="medium" @selected(old('priority', 'medium') === 'medium')>Moyenne — Gêne ou anomalie sans danger immédiat</option>
                            <option value="high" @selected(old('priority') === 'high')>Élevée — Fuite importante ou coupure de service</option>
                            <option value="critical" @selected(old('priority') === 'critical')>Critique — Risque d'inondation ou rupture majeure</option>
                        </select>
                        <div class="validation-feedback" data-field="priority">
                            @error('priority')
                                <span class="field-error-badge" role="alert">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span>{{ $message }}</span>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 4px;">
                            Description des observations <span style="color: var(--coral);">*</span>
                        </label>
                        <textarea name="description"
                                  id="citizen_report_description"
                                  rows="3"
                                  data-label="Description"
                                  data-rules="required|min:10|max:2000"
                                  placeholder="Précisez le lieu exact, l'heure constatée ou l'importance de la fuite (au moins 10 caractères)..."
                                  style="width: 100%; border: 1px solid {{ $errors->has('description') ? 'var(--coral)' : 'var(--line)' }}; border-radius: 5px; padding: 10px 12px; font: inherit; font-size: 12px; background: #fff;">{{ old('description') }}</textarea>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 3px;">
                            <span style="font-size: 11px; color: var(--muted);">Minimum 10 caractères</span>
                            <span id="char_count" style="font-size: 11px; color: var(--muted);">0 / 2000</span>
                        </div>
                        <div class="validation-feedback" data-field="description">
                            @error('description')
                                <span class="field-error-badge" role="alert">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span>{{ $message }}</span>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 4px;">
                                Votre nom
                            </label>
                            <input type="text"
                                   name="reporter_name"
                                   id="report_reporter_name"
                                   value="{{ old('reporter_name', auth()->user()?->name ?? '') }}"
                                   placeholder="Ex: Yassine Mansour"
                                   data-label="Nom du déclarant"
                                   data-rules="letters_only"
                                   style="width: 100%; border: 1px solid {{ $errors->has('reporter_name') ? 'var(--coral)' : 'var(--line)' }}; border-radius: 5px; padding: 9px 10px; font: inherit; font-size: 12px; background: #fff;">
                            <span class="form-hint">Lettres, espaces et tirets uniquement</span>
                            <div class="validation-feedback" data-field="reporter_name">
                                @error('reporter_name')
                                    <span class="field-error-badge" role="alert">
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        <span>{{ $message }}</span>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 4px;">
                                Téléphone de contact
                            </label>
                            <input type="tel"
                                   name="reporter_phone"
                                   id="report_reporter_phone"
                                   value="{{ old('reporter_phone') }}"
                                   placeholder="Ex: +216 98 123 456"
                                   data-label="Téléphone"
                                   data-rules="phone"
                                   style="width: 100%; border: 1px solid {{ $errors->has('reporter_phone') ? 'var(--coral)' : 'var(--line)' }}; border-radius: 5px; padding: 9px 10px; font: inherit; font-size: 12px; background: #fff;">
                            <div class="validation-feedback" data-field="reporter_phone">
                                @error('reporter_phone')
                                    <span class="field-error-badge" role="alert">
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        <span>{{ $message }}</span>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button class="button button-small" type="submit" style="width: 100%; justify-content: center; margin-top: 6px; background: var(--deep);">
                        🚨 Transmettre le signalement aux équipes techniques
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const desc = document.getElementById('citizen_report_description');
    const counter = document.getElementById('char_count');
    if (desc && counter) {
        function updateCount() {
            counter.textContent = desc.value.length + ' / 2000';
            if (desc.value.length > 0 && desc.value.length < 10) {
                counter.style.color = 'var(--coral)';
            } else {
                counter.style.color = 'var(--muted)';
            }
        }
        desc.addEventListener('input', updateCount);
        updateCount();
    }
});
</script>
@endsection
