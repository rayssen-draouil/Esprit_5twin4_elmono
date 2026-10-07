@extends('layouts.back')

@section('content')
<div class="content-heading">
    <div>
        <span class="eyebrow">RÉSEAU HYDRAULIQUE</span>
        <h1>Ajouter une infrastructure</h1>
        <p>Renseignez les détails techniques, l'emplacement et le calendrier d'exploitation du nouvel ouvrage.</p>
    </div>
    <a class="button button-outline" href="{{ route('infrastructures.index') }}">
        ← Retour aux infrastructures
    </a>
</div>

<form method="POST" action="{{ route('infrastructures.store') }}" enctype="multipart/form-data" novalidate id="form_infrastructure" class="validated-form">
    @csrf
    @include('infrastructures.form')
</form>
@endsection
