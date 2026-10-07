@extends('layouts.back')

@section('content')
<div class="content-heading">
    <div>
        <span class="eyebrow">MODIFICATION</span>
        <h1>Modifier l'opération de maintenance</h1>
        <p>{{ $maintenance->reference_code ?? ('#' . $maintenance->id) }} · {{ $maintenance->type }}</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a class="button button-outline" href="{{ route('maintenances.show', $maintenance) }}">
            Consulter
        </a>
        <a class="button button-outline" href="{{ route('maintenances.index') }}">
            ← Liste
        </a>
    </div>
</div>

<form method="POST" action="{{ route('maintenances.update', $maintenance) }}" novalidate id="form_maintenance" class="validated-form">
    @csrf
    @method('PUT')
    @include('maintenances.form')
</form>
@endsection
