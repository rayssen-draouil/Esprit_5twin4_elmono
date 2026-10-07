@extends('layouts.back')

@section('content')
<div class="content-heading">
    <div>
        <span class="eyebrow">GESTION TECHNIQUE</span>
        <h1>Gestion des opérations de maintenance</h1>
        <p>Planifiez les révisions préventives, suivez les interventions correctives et le respect des échéances.</p>
    </div>
    <a class="button" href="{{ route('maintenances.create') }}">
        + Planifier une maintenance
    </a>
</div>

{{-- 1. Real KPI Grid --}}
<div class="kpi-grid project-kpis" style="grid-template-columns: repeat(6, 1fr);">
    <div class="kpi">
        <span>Total opérations</span>
        <strong>{{ $statistics['total'] }}</strong>
        <small class="positive">Historique complet</small>
    </div>
    <div class="kpi">
        <span>Signalements usagers</span>
        <strong style="color: var(--coral);">{{ $statistics['reported'] }}</strong>
        <small>En attente d'approbation</small>
    </div>
    <div class="kpi">
        <span>Planifiées</span>
        <strong style="color: #896116;">{{ $statistics['planned'] }}</strong>
        <small>À venir</small>
    </div>
    <div class="kpi">
        <span>En cours</span>
        <strong style="color: #23638c;">{{ $statistics['in_progress'] }}</strong>
        <small>Technicien sur site</small>
    </div>
    <div class="kpi">
        <span>Terminées</span>
        <strong style="color: #177853;">{{ $statistics['completed'] }}</strong>
        <small>Validées avec rapport</small>
    </div>
    <div class="kpi">
        <span>En retard</span>
        <strong style="color: #a8473d;">{{ $statistics['overdue'] }}</strong>
        <small>Date dépassée</small>
    </div>
</div>

{{-- 2. Advanced Search & Filters --}}
<div class="filter-container">
    <form method="GET" action="{{ route('maintenances.index') }}">
        <input type="hidden" name="view" value="{{ $viewMode }}">

        <div class="filter-main-bar">
            <div class="filter-search-wrap">
                <span class="filter-search-icon">🔍</span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher par réf. (MNT-...), infrastructure, technicien, équipe, description..."
                       aria-label="Rechercher une opération">
            </div>

            <button class="button button-small" type="submit">Rechercher</button>

            @if(count($activeFilters) > 0)
                <a class="button button-small button-outline" href="{{ route('maintenances.index', ['view' => $viewMode]) }}">
                    Réinitialiser
                </a>
            @endif

            <div class="view-switch" style="margin-left: auto;">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}"
                   class="{{ $viewMode === 'table' ? 'active' : '' }}">
                    ☰ Tableau
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}"
                   class="{{ $viewMode === 'calendar' ? 'active' : '' }}">
                    📅 Chronologie
                </a>
            </div>
        </div>

        {{-- Quick Filter Chips --}}
        <div class="quick-chips">
            <span class="quick-chips-label">Filtres rapides :</span>
            <a class="quick-chip {{ !request('quick') && !request('status') ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['view' => $viewMode])) }}">
                Toutes ({{ $statistics['total'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'reported' ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'reported', 'view' => $viewMode])) }}"
               style="border-color: {{ request('quick') === 'reported' ? 'var(--deep)' : '#f3c9c2' }}; color: {{ request('quick') === 'reported' ? '#fff' : 'var(--coral)' }}; background: {{ request('quick') === 'reported' ? 'var(--deep)' : '#fff0ed' }};">
                📢 Signalements usagers ({{ $statistics['reported'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'planned' ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'planned', 'view' => $viewMode])) }}">
                ⏱ Planifiées ({{ $statistics['planned'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'in_progress' ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'in_progress', 'view' => $viewMode])) }}">
                ⚒ En cours ({{ $statistics['in_progress'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'completed' ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'completed', 'view' => $viewMode])) }}">
                ✓ Terminées ({{ $statistics['completed'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'overdue' ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'overdue', 'view' => $viewMode])) }}">
                ⚠ En retard ({{ $statistics['overdue'] }})
            </a>
            <a class="quick-chip {{ request('quick') === 'today' ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'today', 'view' => $viewMode])) }}">
                Aujourd’hui
            </a>
            <a class="quick-chip {{ request('quick') === 'this_week' ? 'active' : '' }}"
               href="{{ route('maintenances.index', array_merge(request()->except(['quick', 'status', 'page']), ['quick' => 'this_week', 'view' => $viewMode])) }}">
                Cette semaine
            </a>
        </div>

        {{-- Advanced Drawer --}}
        <details style="width: 100%; margin-top: 14px;" {{ request()->anyFilled(['infrastructure_id', 'technician_id', 'type', 'priority', 'sort']) ? 'open' : '' }}>
            <summary style="font-size: 12px; font-weight: 700; color: var(--deep); cursor: pointer; padding: 4px 0;">
                ⚙ Filtres avancés & Paramètres de tri
            </summary>
            <div class="advanced-drawer">
                <label>
                    Infrastructure associée
                    <select name="infrastructure_id" onchange="this.form.submit()">
                        <option value="">Toutes les infrastructures</option>
                        @foreach($infrastructures as $infra)
                            <option value="{{ $infra->id }}" @selected((string) request('infrastructure_id') === (string) $infra->id)>
                                {{ $infra->name }} ({{ $infra->reference_code }})
                            </option>
                        @endforeach
                    </select>
                </label>

                <label>
                    Technicien responsable
                    <select name="technician_id" onchange="this.form.submit()">
                        <option value="">Tous les techniciens</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" @selected((string) request('technician_id') === (string) $tech->id)>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label>
                    Type d'intervention
                    <select name="type" onchange="this.form.submit()">
                        <option value="">Tous les types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </label>

                <label>
                    Niveau de priorité
                    <select name="priority" onchange="this.form.submit()">
                        <option value="">Toutes priorités</option>
                        @foreach($priorities as $pval => $plabel)
                            <option value="{{ $pval }}" @selected(request('priority') === $pval)>{{ $plabel }}</option>
                        @endforeach
                    </select>
                </label>

                <label>
                    Trier par
                    <select name="sort" onchange="this.form.submit()">
                        <option value="scheduled_at" @selected(request('sort') === 'scheduled_at')>Date planifiée</option>
                        <option value="priority" @selected(request('sort') === 'priority')>Priorité</option>
                        <option value="cost" @selected(request('sort') === 'cost')>Coût d'intervention</option>
                        <option value="created_at" @selected(request('sort') === 'created_at')>Date d'enregistrement</option>
                    </select>
                </label>

                <div>
                    <button class="button button-small" type="submit" style="height: 38px;">Appliquer</button>
                </div>
            </div>
        </details>
    </form>
</div>

{{-- 3. Active Chips Bar --}}
<div class="active-chips-bar">
    <span class="label">
        <strong>{{ $maintenances->total() }}</strong> opération(s) trouvée(s) :
    </span>

    @foreach($activeFilters as $key => $filter)
        <span class="active-tag">
            {{ $filter['label'] }}
            <a href="{{ route('maintenances.index', array_merge(request()->except([$filter['param'], 'page']), ['view' => $viewMode])) }}">×</a>
        </span>
    @endforeach

    @if(count($activeFilters) > 0)
        <a class="clear-all-link" href="{{ route('maintenances.index', ['view' => $viewMode]) }}">
            Effacer tous les filtres
        </a>
    @endif
</div>

{{-- 4. Content: Table or Timeline/Calendar --}}
@if($maintenances->isEmpty())
    <div class="empty-state">
        <span class="empty-state-icon">⛯</span>
        <h3>Aucune opération de maintenance trouvée</h3>
        <p>Aucun enregistrement ne correspond aux filtres appliqués. Vous pouvez planifier une nouvelle opération dès maintenant.</p>
        <a class="button button-small" href="{{ route('maintenances.create') }}">
            + Planifier une maintenance
        </a>
    </div>
@elseif($viewMode === 'calendar')
    {{-- Timeline / Calendar View --}}
    <div class="panel">
        <div class="panel-heading" style="margin-bottom: 15px;">
            <div>
                <h2>Chronologie des interventions planifiées et récentes</h2>
                <p>Visualisation séquentielle des opérations de maintenance</p>
            </div>
        </div>

        <div class="lifecycle-timeline">
            @foreach($calendarItems as $item)
                <div class="timeline-item">
                    <div class="timeline-marker {{ $item->status === 'completed' ? 'done' : ($item->is_overdue ? 'overdue' : '') }}">
                        {{ $item->status === 'completed' ? '✓' : ($item->is_overdue ? '!' : '⛯') }}
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-content-head">
                            <div>
                                <h4 style="display: inline-flex; align-items: center; gap: 8px;">
                                    <a href="{{ route('maintenances.show', $item) }}" style="color: var(--ink);">
                                        {{ $item->reference_code ?? ('#' . $item->id) }} · {{ $item->type }}
                                    </a>
                                    <span class="badge {{ $item->status_badge_class }}">
                                        {{ $item->display_status_label }}
                                    </span>
                                    <span class="badge {{ $item->priority_badge_class }}">
                                        {{ $item->priority_label }}
                                    </span>
                                </h4>
                                <div style="font-size: 12px; margin-top: 3px;">
                                    Infrastructure :
                                    <a href="{{ route('infrastructures.show', $item->infrastructure) }}" style="color: var(--deep); font-weight: 700;">
                                        {{ $item->infrastructure?->name }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <strong>{{ $item->scheduled_at ? $item->scheduled_at->format('d/m/Y à H:i') : 'Date non fixée' }}</strong>
                                <small style="display: block; color: {{ $item->is_overdue ? 'var(--coral)' : 'var(--muted)' }}; text-align: right;">
                                    {{ $item->relative_time_message }}
                                </small>
                            </div>
                        </div>

                        <p>{{ $item->description }}</p>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; font-size: 11px; color: var(--muted); border-top: 1px solid #f0f5f4; padding-top: 8px;">
                            <span>
                                Technicien : <strong>{{ $item->technician?->name ?? 'Non assigné' }}</strong>
                                @if($item->team) ({{ $item->team }}) @endif
                            </span>
                            <div class="table-actions">
                                <a href="{{ route('maintenances.show', $item) }}">Détails →</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    {{-- Table View --}}
    <section class="panel table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Opération & Réf.</th>
                    <th>Infrastructure</th>
                    <th>Technicien / Équipe</th>
                    <th>Planification & Échéance</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Coût / Durée</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenances as $maintenance)
                    <tr>
                        <td>
                            <strong>
                                <a href="{{ route('maintenances.show', $maintenance) }}" style="color: var(--ink);">
                                    {{ $maintenance->reference_code ?? ('#' . $maintenance->id) }}
                                </a>
                            </strong>
                            <small style="color: var(--muted); font-size: 11px;">
                                {{ $maintenance->type }}
                            </small>
                        </td>
                        <td>
                            @if($maintenance->infrastructure)
                                <strong>
                                    <a href="{{ route('infrastructures.show', $maintenance->infrastructure) }}" style="color: var(--ink);">
                                        {{ $maintenance->infrastructure->name }}
                                    </a>
                                </strong>
                                <small style="color: var(--muted); font-size: 11px;">
                                    {{ $maintenance->infrastructure->reference_code }} · {{ $maintenance->infrastructure->zone?->name }}
                                </small>
                            @else
                                <span style="color: var(--muted);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($maintenance->technician)
                                <strong>{{ $maintenance->technician->name }}</strong>
                                <small style="color: var(--muted); font-size: 11px;">
                                    {{ $maintenance->team ?: $maintenance->technician->speciality }}
                                </small>
                            @else
                                <span style="color: var(--muted);">Non assigné</span>
                                @if($maintenance->team)
                                    <small style="color: var(--muted); font-size: 11px;">{{ $maintenance->team }}</small>
                                @endif
                            @endif
                        </td>
                        <td>
                            <strong>{{ $maintenance->scheduled_at ? $maintenance->scheduled_at->format('d/m/Y H:i') : '—' }}</strong>
                            <small style="font-size: 10px; color: {{ $maintenance->is_overdue ? 'var(--coral)' : 'var(--muted)' }}; font-weight: 600;">
                                {{ $maintenance->relative_time_message }}
                            </small>
                        </td>
                        <td>
                            <span class="badge {{ $maintenance->priority_badge_class }}">
                                {{ $maintenance->priority_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $maintenance->status_badge_class }}">
                                {{ $maintenance->display_status_label }}
                            </span>
                        </td>
                        <td>
                            @if($maintenance->cost)
                                <strong>{{ number_format($maintenance->cost, 2, ',', ' ') }} €</strong>
                            @else
                                <span style="color: var(--muted);">—</span>
                            @endif
                            @if($maintenance->duration_hours)
                                <small style="color: var(--muted); font-size: 10px;">{{ $maintenance->duration_hours }} h</small>
                            @endif
                        </td>
                        <td class="table-actions" style="text-align: right; justify-content: flex-end;">
                            <a href="{{ route('maintenances.show', $maintenance) }}">Voir</a>
                            <a href="{{ route('maintenances.edit', $maintenance) }}">Modifier</a>
                            <form method="POST"
                                  action="{{ route('maintenances.destroy', $maintenance) }}"
                                  onsubmit="return confirm('Confirmer la suppression de cette opération de maintenance ?')">
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
    {{ $maintenances->links() }}
</div>
@endsection
