@extends('layouts.back')

@section('content')
{{-- 1. Header with Metadata & Actions --}}
<div class="content-heading">
    <div>
        <span class="eyebrow">{{ $infrastructure->reference_code ?? ('INF-' . $infrastructure->id) }} · {{ $infrastructure->type }}</span>
        <h1>{{ $infrastructure->name }}</h1>
        <p>
            {{ $infrastructure->zone?->name ?? 'Zone non définie' }}
            @if($infrastructure->location) · {{ $infrastructure->location }} @endif
        </p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a class="button button-outline" href="{{ route('infrastructures.index') }}">
            ← Liste
        </a>
        <a class="button button-outline" href="{{ route('infrastructures.edit', $infrastructure) }}">
            ✎ Modifier
        </a>
        <a class="button" href="{{ route('maintenances.create', ['infrastructure_id' => $infrastructure->id]) }}">
            + Planifier une maintenance
        </a>
    </div>
</div>

{{-- 2. Summary KPI Cards --}}
<div class="kpi-grid project-kpis" style="grid-template-columns: repeat(5, 1fr);">
    <div class="kpi">
        <span>Santé & État</span>
        <strong style="color: {{ $infrastructure->health_score >= 70 ? 'var(--aqua)' : ($infrastructure->health_score >= 50 ? '#aa721c' : 'var(--coral)') }};">
            {{ $infrastructure->health_score }}%
        </strong>
        <small>{{ $infrastructure->health_label }} · {{ $infrastructure->condition_label }}</small>
    </div>

    <div class="kpi">
        <span>Statut actuel</span>
        <strong>
            <span class="badge {{ $infrastructure->status_badge_class }}" style="font-size: 13px; padding: 4px 10px;">
                {{ $infrastructure->status_label }}
            </span>
        </strong>
        <small>Criticité : {{ $infrastructure->criticality_label }}</small>
    </div>

    <div class="kpi">
        <span>Maintenances</span>
        <strong>{{ $metrics['total_maintenances'] }}</strong>
        <small>{{ $metrics['completed_maintenances'] }} terminées · {{ $metrics['upcoming_maintenances'] }} en attente</small>
    </div>

    <div class="kpi">
        <span>Dernière maintenance</span>
        <strong>
            {{ $infrastructure->last_maintenance_date ? $infrastructure->last_maintenance_date->format('d/m/Y') : 'Aucune' }}
        </strong>
        <small>Historique validé</small>
    </div>

    <div class="kpi">
        <span>Budget maintenance</span>
        <strong>{{ number_format($metrics['total_cost'], 2, ',', ' ') }} €</strong>
        <small>Coût total cumulé</small>
    </div>
</div>

{{-- Alerts if overdue or due soon --}}
@if($infrastructure->is_maintenance_overdue)
    <div class="flash-message flash-error" style="margin-bottom: 22px;">
        <strong>⚠ Maintenance en retard :</strong> Cette infrastructure avait une échéance planifiée au {{ $infrastructure->next_maintenance_date?->format('d/m/Y') }}. Une intervention technique doit être planifiée en priorité.
    </div>
@elseif($infrastructure->is_maintenance_due_soon)
    <div class="flash-message flash-warning" style="margin-bottom: 22px;">
        <strong>⏱ Échéance proche :</strong> La prochaine révision réglementaire est prévue le {{ $infrastructure->next_maintenance_date?->format('d/m/Y') }}.
    </div>
@endif

{{-- 3. Detail Grid --}}
<div class="detail-grid">
    {{-- Left Column: Overview + Lifecycle Timeline --}}
    <div>
        {{-- Section: Spécifications & Description --}}
        <div class="panel" style="margin-bottom: 20px;">
            <div class="panel-heading" style="margin-bottom: 15px;">
                <div>
                    <h2>Description & Caractéristiques</h2>
                    <p>Données d'exploitation et dimensionnement de l'infrastructure</p>
                </div>
            </div>

            <p style="color: var(--ink); line-height: 1.6; margin-bottom: 20px;">
                {{ $infrastructure->description ?: 'Aucune description détaillée n’a été enregistrée pour cet ouvrage.' }}
            </p>

            <div class="detail-meta" style="grid-template-columns: repeat(3, 1fr); gap: 16px; background: #fbfdfc; padding: 16px; border-radius: 6px; border: 1px solid var(--line);">
                <div>
                    <span>Capacité nominale</span>
                    <strong>{{ $infrastructure->capacity ?: 'Non renseignée' }}</strong>
                </div>
                <div>
                    <span>Date d'installation</span>
                    <strong>{{ $infrastructure->installation_date ? $infrastructure->installation_date->format('d/m/Y') : '—' }}</strong>
                </div>
                <div>
                    <span>Mise en service</span>
                    <strong>{{ $infrastructure->commissioning_date ? $infrastructure->commissioning_date->format('d/m/Y') : '—' }}</strong>
                </div>
                <div>
                    <span>Coordonnées GPS</span>
                    <strong>
                        @if($infrastructure->latitude && $infrastructure->longitude)
                            {{ number_format($infrastructure->latitude, 4) }}, {{ number_format($infrastructure->longitude, 4) }}
                        @else
                            Non géolocalisé
                        @endif
                    </strong>
                </div>
                <div>
                    <span>Bassin / Zone</span>
                    <strong>{{ $infrastructure->zone?->name ?? '—' }}</strong>
                </div>
                <div>
                    <span>Niveau de criticité</span>
                    <strong>{{ $infrastructure->criticality_label }}</strong>
                </div>
            </div>
        </div>

        {{-- Section: Lifecycle Timeline --}}
        <div class="panel">
            <div class="panel-heading" style="margin-bottom: 10px;">
                <div>
                    <h2>Cycle de vie & Historique des opérations</h2>
                    <p>Traçabilité chronologique des maintenances et contrôles réalisés</p>
                </div>
                <a class="button button-small" href="{{ route('maintenances.create', ['infrastructure_id' => $infrastructure->id]) }}">
                    + Ajouter une opération
                </a>
            </div>

            <div class="lifecycle-timeline">
                {{-- Milestone: Installation --}}
                @if($infrastructure->installation_date)
                    <div class="timeline-item">
                        <div class="timeline-marker done">✓</div>
                        <div class="timeline-content">
                            <div class="timeline-content-head">
                                <h4>Installation & Déploiement initial de l'ouvrage</h4>
                                <small style="color: var(--muted);">{{ $infrastructure->installation_date->format('d/m/Y') }}</small>
                            </div>
                            <p>Mise en place des équipements sur site et raccordement au réseau de surveillance AquaSecure.</p>
                        </div>
                    </div>
                @endif

                {{-- Chronological Maintenances --}}
                @forelse($infrastructure->maintenances as $maintenance)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $maintenance->status === 'completed' ? 'done' : ($maintenance->is_overdue ? 'overdue' : '') }}">
                            {{ $maintenance->status === 'completed' ? '✓' : ($maintenance->is_overdue ? '!' : '⛯') }}
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-content-head">
                                <div>
                                    <h4 style="display: inline-flex; align-items: center; gap: 8px;">
                                        <a href="{{ route('maintenances.show', $maintenance) }}" style="color: var(--ink);">
                                            {{ $maintenance->type }} · {{ $maintenance->reference_code ?? ('#' . $maintenance->id) }}
                                        </a>
                                        <span class="badge {{ $maintenance->status_badge_class }}">
                                            {{ $maintenance->display_status_label }}
                                        </span>
                                    </h4>
                                    @if($maintenance->technician)
                                        <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">
                                            Technicien : <strong>{{ $maintenance->technician->name }}</strong>
                                            @if($maintenance->team) ({{ $maintenance->team }}) @endif
                                        </div>
                                    @endif
                                </div>
                                <small style="color: var(--muted);">
                                    {{ $maintenance->scheduled_at ? $maintenance->scheduled_at->format('d/m/Y à H:i') : 'Date non définie' }}
                                </small>
                            </div>

                            <p>{{ $maintenance->description }}</p>

                            @if($maintenance->result)
                                <div style="margin-top: 8px; padding: 8px 12px; background: #fff; border-left: 3px solid var(--aqua); border-radius: 4px; font-size: 11px;">
                                    <strong>Compte-rendu :</strong> {{ $maintenance->result }}
                                </div>
                            @endif

                            <div style="display: flex; gap: 15px; margin-top: 10px; font-size: 11px; color: var(--muted);">
                                @if($maintenance->cost)
                                    <span>Coût : <strong>{{ number_format($maintenance->cost, 2, ',', ' ') }} €</strong></span>
                                @endif
                                @if($maintenance->duration_hours)
                                    <span>Durée : <strong>{{ $maintenance->duration_hours }} h</strong></span>
                                @endif
                                <a href="{{ route('maintenances.show', $maintenance) }}" style="color: var(--aqua); font-weight: 700; margin-left: auto;">
                                    Consulter l'opération →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="timeline-item">
                        <div class="timeline-marker">○</div>
                        <div class="timeline-content">
                            <div class="timeline-content-head">
                                <h4>Aucune opération de maintenance enregistrée</h4>
                            </div>
                            <p>Cet équipement ne dispose pour le moment d'aucun historique d'intervention.</p>
                            <a class="button button-small" href="{{ route('maintenances.create', ['infrastructure_id' => $infrastructure->id]) }}" style="margin-top: 8px;">
                                Planifier la première maintenance
                            </a>
                        </div>
                    </div>
                @endforelse

                {{-- Milestone: Next Scheduled Maintenance --}}
                @if($infrastructure->next_maintenance_date)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $infrastructure->is_maintenance_overdue ? 'overdue' : '' }}">⏱</div>
                        <div class="timeline-content" style="border-style: dashed;">
                            <div class="timeline-content-head">
                                <h4>Prochaine maintenance recommandée</h4>
                                <small style="color: {{ $infrastructure->is_maintenance_overdue ? 'var(--coral)' : 'var(--deep)' }}; font-weight: 700;">
                                    {{ $infrastructure->next_maintenance_date->format('d/m/Y') }}
                                </small>
                            </div>
                            <p>Échéance préventive calculée pour assurer la longévité et le maintien des normes sanitaires de l'ouvrage.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column: Media, Health Card & Quick Actions --}}
    <div>
        {{-- Health Score Card --}}
        <div class="panel" style="margin-bottom: 20px;">
            <div class="panel-heading" style="margin-bottom: 10px;">
                <div>
                    <h2>Indicateur de Santé</h2>
                    <p>Calcul déterministe basé sur l'état et l'historique</p>
                </div>
            </div>

            <div style="text-align: center; padding: 15px 0;">
                <div style="font-size: 42px; font-weight: 800; letter-spacing: -0.05em; color: {{ $infrastructure->health_score >= 70 ? 'var(--aqua)' : ($infrastructure->health_score >= 50 ? '#aa721c' : 'var(--coral)') }};">
                    {{ $infrastructure->health_score }}%
                </div>
                <strong style="font-size: 14px; color: var(--ink);">{{ $infrastructure->health_label }}</strong>
                <p style="font-size: 11px; color: var(--muted); margin: 4px 0 16px;">
                    État matériel : <strong>{{ $infrastructure->condition_label }}</strong>
                </p>

                <div class="infra-card-health-bar" style="height: 8px; margin: 0 10px;">
                    <i style="width: {{ $infrastructure->health_score }}%; background: {{ $infrastructure->health_score >= 70 ? 'var(--aqua)' : ($infrastructure->health_score >= 50 ? '#aa721c' : 'var(--coral)') }};"></i>
                </div>
            </div>

            <ul style="list-style: none; padding: 0; margin: 15px 0 0; font-size: 11px; color: var(--muted); border-top: 1px solid var(--line); padding-top: 12px;">
                <li style="display: flex; justify-content: space-between; padding: 4px 0;">
                    <span>Statut :</span>
                    <strong style="color: var(--ink);">{{ $infrastructure->status_label }}</strong>
                </li>
                <li style="display: flex; justify-content: space-between; padding: 4px 0;">
                    <span>Condition physique :</span>
                    <strong style="color: var(--ink);">{{ $infrastructure->condition_label }}</strong>
                </li>
                <li style="display: flex; justify-content: space-between; padding: 4px 0;">
                    <span>Dernier contrôle :</span>
                    <strong style="color: var(--ink);">{{ $infrastructure->last_maintenance_date ? $infrastructure->last_maintenance_date->format('d/m/Y') : 'Non renseigné' }}</strong>
                </li>
            </ul>
        </div>

        {{-- Infrastructure Photo Card --}}
        @if($infrastructure->image_path)
            <div class="panel" style="margin-bottom: 20px; padding: 0; overflow: hidden;">
                <img src="{{ asset('storage/' . $infrastructure->image_path) }}"
                     alt="{{ $infrastructure->name }}"
                     style="width: 100%; height: 210px; object-fit: cover; display: block;">
                <div style="padding: 12px 16px; font-size: 11px; color: var(--muted);">
                    Vue photographique de l'ouvrage sur site
                </div>
            </div>
        @endif

        {{-- Maintenance Actions Panel --}}
        <div class="panel">
            <div class="panel-heading" style="margin-bottom: 12px;">
                <div>
                    <h2>Actions Techniques</h2>
                    <p>Gestion opérationnelle de l'ouvrage</p>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a class="button button-small" href="{{ route('maintenances.create', ['infrastructure_id' => $infrastructure->id]) }}" style="justify-content: center;">
                    + Planifier une maintenance sur ce site
                </a>
                <a class="button button-small button-outline" href="{{ route('maintenances.index', ['infrastructure_id' => $infrastructure->id]) }}" style="justify-content: center;">
                    Consulter toutes ses maintenances ({{ $infrastructure->maintenances->count() }})
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
