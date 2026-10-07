@extends('layouts.back')

@section('content')
<div class="content-heading">
    <div>
        <span class="eyebrow">MODIFICATION</span>
        <h1>Modifier l'infrastructure</h1>
        <p>{{ $infrastructure->reference_code ?? ('INF-' . $infrastructure->id) }} · {{ $infrastructure->name }}</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a class="button button-outline" href="{{ route('infrastructures.show', $infrastructure) }}">
            Consulter
        </a>
        <a class="button button-outline" href="{{ route('infrastructures.index') }}">
            ← Liste
        </a>
    </div>
</div>

<form method="POST" action="{{ route('infrastructures.update', $infrastructure) }}" enctype="multipart/form-data" novalidate id="form_infrastructure" class="validated-form">
    @csrf
    @method('PUT')
    @include('infrastructures.form')
</form>
@endsection
