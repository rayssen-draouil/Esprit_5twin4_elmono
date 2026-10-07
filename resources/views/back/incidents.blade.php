@extends('layouts.back')

@section('content')
<div class="content-heading"><div><span class="eyebrow">SURVEILLANCE</span><h1>Incidents</h1><p>Vérifiez les signalements puis suivez les interventions.</p></div></div>

@if(session('success'))<div class="panel" style="margin-bottom:1.5rem">{{ session('success') }}</div>@endif

@if($signalements->isNotEmpty())
<section class="panel table-wrap" style="margin-bottom:2rem">
    <h2 style="padding:1.25rem 1.25rem 0">Signalements à vérifier</h2>
    <table><thead><tr><th>Signalement</th><th>Type</th><th>Localisation</th><th>Photo</th><th>Créer l'incident</th></tr></thead><tbody>
    @foreach($signalements as $signalement)
        <tr>
            <td><strong>#{{ $signalement->id }}</strong><span>{{ $signalement->description }}</span></td>
            <td>{{ $signalement->type }}</td>
            <td>{{ $signalement->location }}</td>
            <td>@if($signalement->photo_path)<img class="incident-photo-thumb" src="{{ asset('storage/'.$signalement->photo_path) }}" alt="Photo du signalement #{{ $signalement->id }}">@else Aucune photo @endif</td>
            <td><form method="POST" action="{{ route('back.signalements.confirm', $signalement) }}">@csrf
                <input name="description" value="{{ $signalement->description }}" aria-label="Description de l'incident">
                <select name="severity"><option value="low">Faible</option><option value="medium" selected>Moyenne</option><option value="high">Élevée</option><option value="critical">Critique</option></select>
                <button class="button button-primary" type="submit">Confirmer</button>
            </form>
            @if(auth()->user()->role === 'admin')
                <div class="admin-actions">
                    <form method="POST" action="{{ route('admin.signalements.destroy', $signalement) }}" onsubmit="return confirm('Supprimer ce signalement ?')">@csrf @method('DELETE')<button class="button button-danger button-small" type="submit">Supprimer</button></form>
                </div>
            @endif</td>
        </tr>
    @endforeach
    </tbody></table>
</section>
@endif

<section class="panel table-wrap"><table><thead><tr><th>Incident</th><th>Site</th><th>Priorité</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead><tbody>
@foreach($incidents as $item)<tr><td><strong>{{ $item['id'] }}</strong><span>{{ $item['title'] }}</span></td><td>{{ $item['site'] }}</td><td><x-ui.badge :variant="strtolower($item['priority'])">{{ $item['priority'] }}</x-ui.badge></td><td><x-ui.badge :variant="strtolower(str_replace(' ', '-', $item['status']))">{{ $item['status'] }}</x-ui.badge></td><td>{{ $item['date'] }}</td><td>
@if(auth()->user()->role === 'admin')
<details><summary>Modifier</summary><form method="POST" action="{{ route('admin.incidents.update', $item['raw_id']) }}">@csrf @method('PATCH')
    <input name="type" value="{{ $item['type'] }}" aria-label="Type">
    <input name="description" value="{{ $item['title'] }}" aria-label="Description">
    <input name="location" value="{{ $item['site'] }}" aria-label="Localisation">
    <select name="severity"><option value="low" @selected($item['severity'] === 'low')>Faible</option><option value="medium" @selected($item['severity'] === 'medium')>Moyenne</option><option value="high" @selected($item['severity'] === 'high')>Élevée</option><option value="critical" @selected($item['severity'] === 'critical')>Critique</option></select>
    <select name="status"><option value="reported" @selected($item['status'] === 'Ouvert')>Ouvert</option><option value="in_progress" @selected($item['status'] === 'En cours')>En cours</option><option value="resolved" @selected($item['status'] === 'Résolu')>Résolu</option><option value="closed" @selected($item['status'] === 'Fermé')>Fermé</option></select>
    <button class="button button-primary button-small" type="submit">Enregistrer</button>
</form></details>
<form class="admin-actions" method="POST" action="{{ route('admin.incidents.destroy', $item['raw_id']) }}" onsubmit="return confirm('Supprimer cet incident ?')">@csrf @method('DELETE')<button class="button button-danger button-small" type="submit">Supprimer</button></form>
@endif
</td></tr>@endforeach
</tbody></table></section>

@if(auth()->user()->role === 'admin')
<section class="panel table-wrap" style="margin-top:2rem"><h2 style="padding:1.25rem 1.25rem 0">Gestion des signalements</h2><table><thead><tr><th>Référence</th><th>Modification</th><th>Incident lié</th><th></th></tr></thead><tbody>
@foreach($allSignalements as $signalement)<tr><td>#{{ $signalement->id }}</td><td><form method="POST" action="{{ route('admin.signalements.update', $signalement) }}">@csrf @method('PATCH')
    <input name="type" value="{{ $signalement->type }}" aria-label="Type">
    <input name="description" value="{{ $signalement->description }}" aria-label="Description">
    <input name="location" value="{{ $signalement->location }}" aria-label="Localisation">
    <input name="priority" value="{{ $signalement->priority }}" aria-label="Priorité">
    <select name="status"><option value="new" @selected($signalement->status === 'new')>Nouveau</option><option value="in_progress" @selected($signalement->status === 'in_progress')>En cours</option><option value="resolved" @selected($signalement->status === 'resolved')>Résolu</option></select>
    <button class="button button-primary button-small" type="submit">Enregistrer</button>
</form></td><td>{{ $signalement->incident ? 'INC-'.str_pad($signalement->incident->id, 4, '0', STR_PAD_LEFT) : 'Aucun' }}</td><td><form class="admin-actions" method="POST" action="{{ route('admin.signalements.destroy', $signalement) }}" onsubmit="return confirm('Supprimer ce signalement ?')">@csrf @method('DELETE')<button class="button button-danger button-small" type="submit">Supprimer</button></form></td></tr>@endforeach
</tbody></table></section>
@endif
@endsection
