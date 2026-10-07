@extends('layouts.back')

@section('content')
<div class="content-heading">
    <div>
        <span class="eyebrow">OPÉRATION TECHNIQUE · {{ $maintenance->type }}</span>
        <h1 style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            {{ $maintenance->reference_code ?? ('#' . $maintenance->id) }}
            <span class="badge {{ $maintenance->status_badge_class }}" style="font-size: 14px; padding: 4px 10px;">
                {{ $maintenance->display_status_label }}
            </span>
            <span class="badge {{ $maintenance->priority_badge_class }}" style="font-size: 14px; padding: 4px 10px;">
                Priorité {{ $maintenance->priority_label }}
            </span>
        </h1>
        <p>
            Prévue le {{ $maintenance->scheduled_at ? $maintenance->scheduled_at->format('d/m/Y à H:i') : 'Date non fixée' }}
            ({{ $maintenance->relative_time_message }})
        </p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a class="button button-outline" href="{{ route('maintenances.index') }}">
            ← Liste des maintenances
        </a>
        <a class="button button-outline" href="{{ route('maintenances.edit', $maintenance) }}">
            ✎ Modifier
        </a>
        @if($maintenance->infrastructure)
            <a class="button" href="{{ route('infrastructures.show', $maintenance->infrastructure) }}">
                ▦ Voir l'infrastructure
            </a>
        @endif
    </div>
</div>

{{-- Citizen Report Approval Banner --}}
@if($maintenance->status === 'reported')
    <div class="panel" style="background: #fff4db; border-color: #f7da92; margin-bottom: 22px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <span class="eyebrow" style="color: #9a6814;">SIGNALEMENT CITOYEN EN ATTENTE D'ACTION</span>
                <h3 style="margin: 4px 0; font-size: 17px; color: var(--ink);">
                    Ce dysfonctionnement a été signalé par un usager et requiert une prise en charge technique.
                </h3>
                <p style="margin: 0; font-size: 13px; color: var(--muted);">
                    Vous pouvez valider l'alerte pour mandater immédiatement une équipe et basculer l'opération en cours d'intervention.
                </p>
            </div>
            <form method="POST" action="{{ route('maintenances.start', $maintenance) }}" style="display: flex; gap: 10px; align-items: center;">
                @csrf
                <button class="button" type="submit" style="background: var(--aqua);">
                    ⚡ Valider & Démarrer la maintenance
                </button>
            </form>
        </div>
    </div>
@endif

{{-- Lifecycle Progress Stepper: Planned -> In Progress -> Completed --}}
<div class="stepper-wrap">
    @if($maintenance->status === 'reported')
        <div class="stepper-step completed">
            <div class="stepper-step-num">!</div>
            <span>Signalement reçu</span>
        </div>
        <div class="stepper-line"></div>
    @endif

    <div class="stepper-step {{ in_array($maintenance->status, ['planned', 'in_progress', 'completed']) ? 'completed' : ($maintenance->status === 'reported' ? '' : 'active') }}">
        <div class="stepper-step-num">1</div>
        <span>Planifiée</span>
    </div>
    <div class="stepper-line {{ in_array($maintenance->status, ['in_progress', 'completed']) ? 'completed' : '' }}"></div>

    <div class="stepper-step {{ $maintenance->status === 'completed' ? 'completed' : ($maintenance->status === 'in_progress' ? 'active' : '') }}">
        <div class="stepper-step-num">2</div>
        <span>En cours d'exécution</span>
    </div>
    <div class="stepper-line {{ $maintenance->status === 'completed' ? 'completed' : '' }}"></div>

    <div class="stepper-step {{ $maintenance->status === 'completed' ? 'completed' : '' }}">
        <div class="stepper-step-num">3</div>
        <span>Terminée & Validée</span>
    </div>

    @if($maintenance->status === 'cancelled')
        <div style="margin-left: auto;">
            <span class="badge badge-neutral" style="font-size: 12px;">Opération annulée</span>
        </div>
    @elseif($maintenance->is_overdue)
        <div style="margin-left: auto;">
            <span class="badge badge-danger" style="font-size: 12px;">Échéance dépassée</span>
        </div>
    @endif
</div>

{{-- Main Details Grid --}}
<div class="detail-grid">
    {{-- Left Column: Description, Technical Report & Dates --}}
    <div>
        <div class="panel" style="margin-bottom: 20px;">
            <div class="panel-heading" style="margin-bottom: 14px;">
                <div>
                    <h2>Descriptif des Travaux</h2>
                    <p>Objectifs et périmètre de l'intervention</p>
                </div>
            </div>

            <p style="color: var(--ink); line-height: 1.6; margin-bottom: 22px;">
                {{ $maintenance->description ?: 'Aucune description spécifique fournie pour cette maintenance.' }}
            </p>

            <div class="detail-meta" style="grid-template-columns: repeat(3, 1fr); gap: 14px; background: #fbfdfc; padding: 16px; border-radius: 6px; border: 1px solid var(--line);">
                <div>
                    <span>Date planifiée</span>
                    <strong>{{ $maintenance->scheduled_at ? $maintenance->scheduled_at->format('d/m/Y H:i') : '—' }}</strong>
                </div>
                <div>
                    <span>Début réel</span>
                    <strong>{{ $maintenance->started_at ? $maintenance->started_at->format('d/m/Y H:i') : '—' }}</strong>
                </div>
                <div>
                    <span>Achèvement effectif</span>
                    <strong>{{ $maintenance->completed_at ? $maintenance->completed_at->format('d/m/Y H:i') : '—' }}</strong>
                </div>
                <div>
                    <span>Coût de l'opération</span>
                    <strong>{{ $maintenance->cost ? number_format($maintenance->cost, 2, ',', ' ') . ' €' : 'Non chiffré' }}</strong>
                </div>
                <div>
                    <span>Temps passé</span>
                    <strong>{{ $maintenance->duration_hours ? $maintenance->duration_hours . ' heures' : 'Non précisé' }}</strong>
                </div>
                <div>
                    <span>Prochaine révision</span>
                    <strong>{{ $maintenance->next_maintenance_date ? $maintenance->next_maintenance_date->format('d/m/Y') : 'Non planifiée' }}</strong>
                </div>
            </div>
        </div>

        {{-- Compte-rendu technique --}}
        <div class="panel">
            <div class="panel-heading" style="margin-bottom: 14px;">
                <div>
                    <h2>Compte-Rendu & Rapport d'Intervention</h2>
                    <p>Observations et conclusions rédigées par l'équipe technique</p>
                </div>
            </div>

            @if($maintenance->result)
                <div style="background: #fbfdfc; border-left: 4px solid var(--aqua); border-radius: 6px; padding: 16px 20px; font-size: 13px; line-height: 1.6; color: var(--ink);">
                    {{ $maintenance->result }}
                </div>
            @else
                <div style="background: #fbfdfc; border: 1px dashed var(--line); border-radius: 6px; padding: 22px; text-align: center; color: var(--muted); font-size: 13px;">
                    Aucun compte-rendu technique n'a encore été consigné pour cette opération.
                    <br>
                    <a class="text-link" href="{{ route('maintenances.edit', $maintenance) }}" style="margin-top: 8px;">
                        Ajouter un compte-rendu d'intervention →
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Column: Associated Infrastructure & Technician Cards --}}
    <div>
        {{-- Infrastructure Card --}}
        @if($maintenance->infrastructure)
            <div class="panel" style="margin-bottom: 20px;">
                <div class="panel-heading" style="margin-bottom: 12px;">
                    <div>
                        <span class="eyebrow">INFRASTRUCTURE RATTACHÉE</span>
                        <h2 style="margin-top: 2px;">
                            <a href="{{ route('infrastructures.show', $maintenance->infrastructure) }}" style="color: var(--ink);">
                                {{ $maintenance->infrastructure->name }}
                            </a>
                        </h2>
                    </div>
                    <span class="badge {{ $maintenance->infrastructure->status_badge_class }}">
                        {{ $maintenance->infrastructure->status_label }}
                    </span>
                </div>

                <div class="infra-card-meta-list" style="margin-bottom: 14px;">
                    <div>
                        <span>Référence</span>
                        <strong>{{ $maintenance->infrastructure->reference_code }}</strong>
                    </div>
                    <div>
                        <span>Type</span>
                        <strong>{{ $maintenance->infrastructure->type }}</strong>
                    </div>
                    <div>
                        <span>Bassin</span>
                        <strong>{{ $maintenance->infrastructure->zone?->name ?? '—' }}</strong>
                    </div>
                    <div>
                        <span>Santé site</span>
                        <strong>{{ $maintenance->infrastructure->health_score }}% ({{ $maintenance->infrastructure->health_label }})</strong>
                    </div>
                </div>

                <div style="text-align: right;">
                    <a class="button button-small button-outline" href="{{ route('infrastructures.show', $maintenance->infrastructure) }}">
                        Consulter la fiche infrastructure →
                    </a>
                </div>
            </div>
        @endif

        {{-- Technician & Team Card --}}
        <div class="panel">
            <div class="panel-heading" style="margin-bottom: 12px;">
                <div>
                    <span class="eyebrow">RESSOURCE TECHNIQUE</span>
                    <h2 style="margin-top: 2px;">Intervenants</h2>
                </div>
            </div>

            @if($maintenance->technician)
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                    <span class="avatar" style="width: 42px; height: 42px; font-size: 14px;">
                        {{ collect(explode(' ', $maintenance->technician->name))->map(fn ($n) => substr($n, 0, 1))->join('') }}
                    </span>
                    <div>
                        <strong style="font-size: 15px; color: var(--ink);">
                            <a href="{{ route('techniciens.show', $maintenance->technician) }}" style="color: var(--ink);">
                                {{ $maintenance->technician->name }}
                            </a>
                        </strong>
                        <small style="color: var(--muted); display: block; font-size: 11px;">
                            Spécialité : {{ $maintenance->technician->speciality ?? 'Généraliste' }}
                        </small>
                    </div>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; font-size: 12px; color: var(--muted);">
                    @if($maintenance->technician->phone)
                        <li style="padding: 4px 0;">📞 {{ $maintenance->technician->phone }}</li>
                    @endif
                    @if($maintenance->technician->email)
                        <li style="padding: 4px 0;">✉ {{ $maintenance->technician->email }}</li>
                    @endif
                    @if($maintenance->team)
                        <li style="padding: 4px 0;">👥 Équipe : <strong>{{ $maintenance->team }}</strong></li>
                    @endif
                </ul>
            @else
                <p style="font-size: 12px; color: var(--muted); margin: 0 0 10px;">
                    Aucun technicien individuel n'est affecté à cette opération.
                </p>
                @if($maintenance->team)
                    <p style="font-size: 12px; color: var(--ink); margin: 0;">
                        Équipe en charge : <strong>{{ $maintenance->team }}</strong>
                    </p>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
