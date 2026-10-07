@extends('layouts.back')

@section('content')
<div class="content-heading">
    <div>
        <span class="eyebrow">RÉSEAU HYDRAULIQUE</span>
        <h1>Gestion des infrastructures</h1>
        <p>Surveillez les ouvrages, stations de pompage, usines et leur état opérationnel.</p>
    </div>
    <a class="button" href="{{ route('infrastructures.create') }}">
        + Nouvelle infrastructure
    </a>
</div>

{{-- 1. Real KPI Grid --}}
<div class="kpi-grid project-kpis" style="grid-template-columns: repeat(5, 1fr);">
    <div class="kpi">
        <span>Total infrastructures</span>
        <strong>{{ $statistics['total'] }}</strong>
        <small class="positive">Ouvrages référencés</small>
    </div>
    <div class="kpi">
        <span>Opérationnelles</span>
        <strong style="color: #177853;">{{ $statistics['operational'] }}</strong>
        <small>{{ $statistics['total'] > 0 ? round(($statistics['operational'] / $statistics['total']) * 100) : 0 }}% en service nominal</small>
    </div>
    <div class="kpi">
        <span>En maintenance</span>
        <strong style="color: #aa721c;">{{ $statistics['maintenance'] }}</strong>
        <small>Intervention active</small>
    </div>
    <div class="kpi">
        <span>Critiques / Hors ligne</span>
        <strong style="color: #a8473d;">{{ $statistics['critical'] }}</strong>
        <small>À traiter en urgence</small>
    </div>
    <div class="kpi">
        <span>Maintenance requise</span>
        <strong style="color: var(--deep);">{{ $statistics['due_soon'] }}</strong>
        <small>Sous 14 jours</small>
    </div>
</div>

{{-- 2. Advanced Search & Filter Experience --}}
<div class="filter-container">
    <form method="GET" action="{{ route('infrastructures.index') }}" id="infraFilterForm">
        <input type="hidden" name="view" value="{{ $viewMode }}">

        <div class="filter-main-bar">
            <div class="filter-search-wrap">
                <span class="filter-search-icon">🔍</span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher par nom, référence (ex: INF-2023-001), type ou localisation..."
                       aria-label="Rechercher une infrastructure">
            </div>

            <button class="button button-small" type="submit">Rechercher</button>

            @if(count($activeFilters) > 0)
                <a class="button button-small button-outline" href="{{ route('infrastructures.index', ['view' => $viewMode]) }}">
                    Réinitialiser
                </a>
            @endif

            <div class="view-switch" style="margin-left: auto;">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}"
                   class="{{ $viewMode === 'table' ? 'active' : '' }}"
                   title="Vue Tableau">
                    ☰ Tableau
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                   class="{{ $viewMode === 'grid' ? 'active' : '' }}"
                   title="Vue Grille">
                    ▦ Grille
                </a>
            </div>
        </div>

        {{-- Quick Filter Chips --}}
        <div class="quick-chips">
            <span class="quick-chips-label">Filtres rapides :</span>
            <a class="quick-chip {{ !request('quick') && !request('status') ? 'active' : '' }}"
               href="{{ route('infrastructures.index', array_merge(request()->except(['quick', 'status', 'page']), ['view' => $viewMode])) }}">
                Tous ({{ $statistics['total'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'operational' ? 'active' : '' }}"
               href="{{ route('infrastructures.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'operational', 'view' => $viewMode])) }}">
                ● Opérationnelles ({{ $statistics['operational'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'maintenance' ? 'active' : '' }}"
               href="{{ route('infrastructures.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'maintenance', 'view' => $viewMode])) }}">
                ▲ En maintenance ({{ $statistics['maintenance'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'critical' ? 'active' : '' }}"
               href="{{ route('infrastructures.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'critical', 'view' => $viewMode])) }}">
                ⚠ Critiques ({{ $statistics['critical'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'due_soon' ? 'active' : '' }}"
               href="{{ route('infrastructures.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'due_soon', 'view' => $viewMode])) }}">
                ⏱ Échéance proche ({{ $statistics['due_soon'] }})
            </a>
        </div>

        {{-- Advanced Filters Panel / Drawer --}}
        <details style="width: 100%; margin-top: 14px;" {{ request()->anyFilled(['zone_id', 'type', 'condition', 'criticality', 'sort']) ? 'open' : '' }}>
            <summary style="font-size: 12px; font-weight: 700; color: var(--deep); cursor: pointer; padding: 4px 0;">
                ⚙ Filtres avancés & Tri
            </summary>
            <div class="advanced-drawer">
                <label>
                    Zone géographique
                    <select name="zone_id" onchange="this.form.submit()">
                        <option value="">Toutes les zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" @selected((string) request('zone_id') === (string) $zone->id)>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label>
                    Type d'infrastructure
                    <select name="type" onchange="this.form.submit()">
                        <option value="">Tous les types</option>
                        @foreach($types as $typeOption)
                            <option value="{{ $typeOption }}" @selected(request('type') === $typeOption)>
                                {{ $typeOption }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label>
                    État général
                    <select name="condition" onchange="this.form.submit()">
                        <option value="">Tous les états</option>
                        <option value="excellent" @selected(request('condition') === 'excellent')>Excellent</option>
                        <option value="good" @selected(request('condition') === 'good')>Bon état</option>
                        <option value="fair" @selected(request('condition') === 'fair')>Passable</option>
                        <option value="poor" @selected(request('condition') === 'poor')>Dégradé</option>
                        <option value="critical" @selected(request('condition') === 'critical')>Critique</option>
                    </select>
                </label>

                <label>
                    Criticité
                    <select name="criticality" onchange="this.form.submit()">
                        <option value="">Toutes criticités</option>
                        <option value="low" @selected(request('criticality') === 'low')>Faible</option>
                        <option value="medium" @selected(request('criticality') === 'medium')>Moyenne</option>
                        <option value="high" @selected(request('criticality') === 'high')>Élevée</option>
                        <option value="vital" @selected(request('criticality') === 'vital')>Vitale</option>
                    </select>
                </label>

                <label>
                    Trier par
                    <select name="sort" onchange="this.form.submit()">
                        <option value="created_at" @selected(request('sort') === 'created_at')>Date de création</option>
                        <option value="name" @selected(request('sort') === 'name')>Nom</option>
                        <option value="reference_code" @selected(request('sort') === 'reference_code')>Référence</option>
                        <option value="next_maintenance_date" @selected(request('sort') === 'next_maintenance_date')>Prochaine maintenance</option>
                    </select>
                </label>

                <div style="display: flex; gap: 8px;">
                    <button class="button button-small" type="submit" style="height: 38px;">Appliquer</button>
                </div>
            </div>
        </details>
    </form>
</div>

{{-- 3. Active Filters Chips & Result Count --}}
<div class="active-chips-bar">
    <span class="label">
        <strong>{{ $infrastructures->total() }}</strong> infrastructure(s) trouvée(s) :
    </span>

    @foreach($activeFilters as $key => $filter)
        <span class="active-tag">
            {{ $filter['label'] }}
            <a href="{{ route('infrastructures.index', array_merge(request()->except([$filter['param'], 'page']), ['view' => $viewMode])) }}"
               title="Retirer ce filtre">×</a>
        </span>
    @endforeach

    @if(count($activeFilters) > 0)
        <a class="clear-all-link" href="{{ route('infrastructures.index', ['view' => $viewMode]) }}">
            Effacer tous les filtres
        </a>
    @endif
</div>

{{-- 4. Main Listing: Table View or Card View --}}
@if($infrastructures->isEmpty())
    <div class="empty-state">
        <span class="empty-state-icon">▦</span>
        <h3>Aucune infrastructure ne correspond à vos critères</h3>
        <p>Essayez d'ajuster vos termes de recherche ou retirez un ou plusieurs filtres pour afficher l'ensemble du réseau AquaSecure.</p>
        <a class="button button-small" href="{{ route('infrastructures.index') }}">
            Réinitialiser les filtres
        </a>
    </div>
@elseif($viewMode === 'grid')
    {{-- Card / Grid View --}}
    <div class="infra-cards-grid">
        @foreach($infrastructures as $infra)
            <article class="infra-card-item">
                <div class="infra-card-header">
                    <div>
                        <span class="infra-card-ref">{{ $infra->reference_code ?? ('INF-' . $infra->id) }}</span>
                        <h3>
                            <a href="{{ route('infrastructures.show', $infra) }}">{{ $infra->name }}</a>
                        </h3>
                    </div>
                    <span class="badge {{ $infra->status_badge_class }}">
                        {{ $infra->status_label }}
                    </span>
                </div>

                <div class="infra-card-body">
                    <p class="infra-card-desc">
                        {{ \Illuminate\Support\Str::limit($infra->description ?? 'Aucune description disponible pour cet équipement.', 95) }}
                    </p>

                    <div class="infra-card-meta-list">
                        <div>
                            <span>Type</span>
                            <strong>{{ $infra->type }}</strong>
                        </div>
                        <div>
                            <span>Zone</span>
                            <strong>{{ $infra->zone?->name ?? 'Non définie' }}</strong>
                        </div>
                        <div>
                            <span>Capacité</span>
                            <strong>{{ $infra->capacity ?? 'Standard' }}</strong>
                        </div>
                        <div>
                            <span>État</span>
                            <strong>{{ $infra->condition_label }}</strong>
                        </div>
                    </div>

                    <div class="infra-card-health">
                        <div style="display: flex; justify-content: space-between; font-size: 11px;">
                            <span>Santé de l'ouvrage</span>
                            <strong>{{ $infra->health_score }}% ({{ $infra->health_label }})</strong>
                        </div>
                        <div class="infra-card-health-bar">
                            <i style="width: {{ $infra->health_score }}%; background: {{ $infra->health_score >= 70 ? 'var(--aqua)' : ($infra->health_score >= 50 ? '#aa721c' : 'var(--coral)') }};"></i>
                        </div>
                    </div>

                    @if($infra->is_maintenance_overdue)
                        <div style="background: #fff0ed; border: 1px solid #f3c9c2; border-radius: 5px; padding: 7px 10px; font-size: 11px; color: #a8473d;">
                            ⚠ Maintenance en retard (échéance : {{ $infra->next_maintenance_date?->format('d/m/Y') }})
                        </div>
                    @elseif($infra->is_maintenance_due_soon)
                        <div style="background: #fff4db; border: 1px solid #fae1a6; border-radius: 5px; padding: 7px 10px; font-size: 11px; color: #896116;">
                            ⏱ Maintenance requise le {{ $infra->next_maintenance_date?->format('d/m/Y') }}
                        </div>
                    @endif
                </div>

                <div class="infra-card-footer">
                    <span style="font-size: 11px; color: var(--muted);">
                        ⚒ {{ $infra->maintenances->count() }} maintenance(s)
                    </span>
                    <div class="table-actions">
                        <a href="{{ route('infrastructures.show', $infra) }}">Consulter</a>
                        <a href="{{ route('infrastructures.edit', $infra) }}">Modifier</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    {{-- Table View --}}
    <section class="panel table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Infrastructure</th>
                    <th>Type</th>
                    <th>Zone & Lieu</th>
                    <th>Santé</th>
                    <th>Statut / État</th>
                    <th>Prochaine Maintenance</th>
                    <th>Maintenances</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($infrastructures as $infra)
                    <tr>
                        <td>
                            <strong>
                                <a href="{{ route('infrastructures.show', $infra) }}" style="color: var(--ink);">
                                    {{ $infra->name }}
                                </a>
                            </strong>
                            <small style="color: var(--muted); font-size: 11px;">
                                {{ $infra->reference_code ?? ('INF-' . $infra->id) }}
                                @if($infra->capacity) · {{ $infra->capacity }} @endif
                            </small>
                        </td>
                        <td>{{ $infra->type }}</td>
                        <td>
                            <strong>{{ $infra->zone?->name ?? '—' }}</strong>
                            <small style="color: var(--muted); font-size: 11px;">
                                {{ \Illuminate\Support\Str::limit($infra->location ?? 'Non précisé', 28) }}
                            </small>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="health" style="width: 70px;">
                                    <i style="width: {{ $infra->health_score }}%; background: {{ $infra->health_score >= 70 ? 'var(--aqua)' : ($infra->health_score >= 50 ? '#aa721c' : 'var(--coral)') }};"></i>
                                </div>
                                <span style="font-size: 11px; font-weight: 700;">{{ $infra->health_score }}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $infra->status_badge_class }}">
                                {{ $infra->status_label }}
                            </span>
                            <br>
                            <span class="badge {{ $infra->condition_badge_class }}" style="margin-top: 3px; font-size: 9px;">
                                {{ $infra->condition_label }}
                            </span>
                        </td>
                        <td>
                            @if($infra->next_maintenance_date)
                                <span>{{ $infra->next_maintenance_date->format('d/m/Y') }}</span>
                                @if($infra->is_maintenance_overdue)
                                    <span class="badge badge-danger" style="margin-top: 2px;">En retard</span>
                                @elseif($infra->is_maintenance_due_soon)
                                    <span class="badge badge-warning" style="margin-top: 2px;">Sous 14j</span>
                                @endif
                            @else
                                <span style="color: var(--muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('maintenances.index', ['infrastructure_id' => $infra->id]) }}"
                               style="color: var(--deep); font-weight: 600; font-size: 11px;">
                                ⚒ {{ $infra->maintenances->count() }} op.
                            </a>
                        </td>
                        <td class="table-actions" style="text-align: right; justify-content: flex-end;">
                            <a href="{{ route('infrastructures.show', $infra) }}">Voir</a>
                            <a href="{{ route('infrastructures.edit', $infra) }}">Modifier</a>
                            <form method="POST"
                                  action="{{ route('infrastructures.destroy', $infra) }}"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette infrastructure ? Toutes les opérations de maintenance associées seront également supprimées.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--coral);">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endif

{{-- 5. Pagination --}}
<div style="margin-top: 20px;">
    {{ $infrastructures->links() }}
</div>
@endsection
