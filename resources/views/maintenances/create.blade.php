@extends('layouts.back')

@section('content')
<div class="content-heading">
    <div>
        <span class="eyebrow">GESTION TECHNIQUE</span>
        <h1>Planifier une maintenance</h1>
        <p>Enregistrez une nouvelle opération de maintenance préventive, corrective ou réglementaire.</p>
    </div>
    <a class="button button-outline" href="{{ route('maintenances.index') }}">
        ← Retour aux maintenances
    </a>
</div>

<form method="POST" action="{{ route('maintenances.store') }}" novalidate id="form_maintenance" class="validated-form">
    @csrf
    @include('maintenances.form')
</form>
@endsection
