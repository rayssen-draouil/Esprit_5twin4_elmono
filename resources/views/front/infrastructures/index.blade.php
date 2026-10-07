@extends('layouts.front')

@section('content')
{{-- 1. Hero Section --}}
<section class="page-hero compact">
    <span class="eyebrow">RÉSEAU HYDRAULIQUE & SURVEILLANCE</span>
    <h1>Nos infrastructures<br><em>en temps réel.</em></h1>
    <p>
        Accédez à la cartographie et au statut opérationnel des ouvrages et stations assurant la sécurité hydrique des territoires partenaires AquaSecure.
    </p>

    <div style="display: flex; gap: 30px; margin-top: 25px; flex-wrap: wrap;">
        <div>
            <span style="font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .05em;">Ouvrages surveillés</span>
            <strong style="display: block; font-size: 24px; color: var(--deep);">{{ $totalCount }}</strong>
        </div>
        <div>
            <span style="font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .05em;">En service nominal</span>
            <strong style="display: block; font-size: 24px; color: var(--aqua);">{{ $operationalCount }}</strong>
        </div>
        <div>
            <span style="font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .05em;">Bassins couverts</span>
            <strong style="display: block; font-size: 24px; color: var(--deep);">{{ $zones->count() }}</strong>
        </div>
    </div>
</section>

{{-- 2. Public Search & Filter Bar --}}
<section class="section" style="padding-top: 35px; padding-bottom: 25px;">
    <div class="filter-container">
        <form method="GET" action="{{ route('front.infrastructures.index') }}">
            <div class="filter-main-bar">
                <div class="filter-search-wrap">
                    <span class="filter-search-icon">🔍</span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher par nom de station, type d'ouvrage, bassin ou ville..."
                           aria-label="Rechercher une infrastructure">
                </div>

                <button class="button button-small" type="submit">Rechercher</button>

                @if(request()->anyFilled(['search', 'quick', 'zone_id', 'type']))
                    <a class="button button-small button-outline" href="{{ route('front.infrastructures.index') }}">
                        Effacer les filtres
                    </a>
                @endif
            </div>

            <div style="margin-top: 8px; font-size: 11px; color: var(--muted);">
                💡 <em>Astuce : Essayez « Barrage », « Station de pompage », « Sète » ou « Rhône » pour filtrer rapidement les sites.</em>
            </div>

            {{-- Quick Filter Tabs --}}
            <div class="quick-chips" style="margin-top: 15px;">
                <span class="quick-chips-label">Statut :</span>
                <a class="quick-chip {{ !request('quick') ? 'active' : '' }}"
                   href="{{ route('front.infrastructures.index', request()->except(['quick', 'page'])) }}">
                    Toutes les infrastructures ({{ $totalCount }})
                </a>
                <a class="quick-chip {{ request('quick') === 'operational' ? 'active' : '' }}"
                   href="{{ route('front.infrastructures.index', array_merge(request()->except(['quick', 'page']), ['quick' => 'operational'])) }}">
                    ● Opérationnelles ({{ $operationalCount }})
                </a>
                <a class="quick-chip {{ request('quick') === 'maintenance' ? 'active' : '' }}"
                   href="{{ route('front.infrastructures.index', array_merge(request()->except(['quick', 'page']), ['quick' => 'maintenance'])) }}">
                    ▲ En maintenance programmée
                </a>
            </div>

            {{-- Filter Selectors --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f0f5f4;">
                <label style="font-size: 11px; font-weight: 700; color: var(--ink);">
                    Bassin géographique
                    <select name="zone_id" onchange="this.form.submit()" style="margin-top: 4px; padding: 9px; width: 100%; border: 1px solid var(--line); border-radius: 5px; font: inherit;">
                        <option value="">Tous les territoires</option>
                        @foreach($zones as $z)
                            <option value="{{ $z->id }}" @selected((string) request('zone_id') === (string) $z->id)>
                                {{ $z->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label style="font-size: 11px; font-weight: 700; color: var(--ink);">
                    Type d'ouvrage
                    <select name="type" onchange="this.form.submit()" style="margin-top: 4px; padding: 9px; width: 100%; border: 1px solid var(--line); border-radius: 5px; font: inherit;">
                        <option value="">Tous les types d'équipements</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" @selected(request('type') === $t)>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
        </form>
    </div>

    {{-- Result Counter --}}
    <div style="margin-bottom: 20px; font-size: 13px; color: var(--muted);">
        <strong>{{ $infrastructures->total() }}</strong> équipement(s) répertorié(s) :
    </div>

    {{-- Grid of Infrastructures --}}
    @if($infrastructures->isEmpty())
        <div class="empty-state">
            <span class="empty-state-icon">💧</span>
            <h3>Aucune infrastructure ne correspond à votre recherche</h3>
            <p>Aucun équipement public ne correspond à vos critères actuels. Vous pouvez réinitialiser les filtres pour afficher l'ensemble du réseau surveillé.</p>
            <a class="button button-small" href="{{ route('front.infrastructures.index') }}">
                Effacer les filtres
            </a>
        </div>
    @else
        <div class="project-grid">
            @foreach($infrastructures as $infra)
                <article class="project-card" style="display: flex; flex-direction: column;">
                    <div class="project-art art-{{ $loop->index % 3 }}">
                        <span>{{ $infra->status_label }}</span>
                    </div>

                    <div class="card-body" style="flex: 1; display: flex; flex-direction: column;">
                        <span class="eyebrow" style="font-size: 10px; margin-bottom: 4px;">
                            {{ $infra->reference_code ?? ('INF-' . $infra->id) }} · {{ $infra->type }}
                        </span>

                        <h3 style="margin-bottom: 6px;">
                            <a href="{{ route('front.infrastructures.show', $infra->reference_code ?? $infra->id) }}" style="color: var(--ink);">
                                {{ $infra->name }}
                            </a>
                        </h3>

                        <p style="margin-bottom: 12px;">
                            {{ $infra->zone?->name ?? 'France' }}
                            @if($infra->location) · {{ \Illuminate\Support\Str::limit($infra->location, 32) }} @endif
                        </p>

                        <div class="progress" style="margin-top: auto;">
                            <i style="width: {{ $infra->health_score }}%; background: {{ $infra->health_score >= 70 ? 'var(--aqua)' : ($infra->health_score >= 50 ? '#aa721c' : 'var(--coral)') }};"></i>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px; font-size: 11px; color: var(--muted);">
                            <span>Indice de santé : <strong>{{ $infra->health_score }}%</strong></span>
                            <span>{{ $infra->condition_label }}</span>
                        </div>

                        <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
                            <small style="color: var(--muted);">
                                @if($infra->next_maintenance_date)
                                    Contrôle prévu le {{ $infra->next_maintenance_date->format('d/m/Y') }}
                                @else
                                    Surveillance continue
                                @endif
                            </small>
                            <a class="text-link" href="{{ route('front.infrastructures.show', $infra->reference_code ?? $infra->id) }}" style="margin: 0; font-size: 12px;">
                                Fiche détaillée →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 35px;">
            {{ $infrastructures->links() }}
        </div>
    @endif
</section>
@endsection
