@extends('layouts.back')
@section('content')
<div class="content-heading"><div><span class="eyebrow">ÉQUIPES</span><h1>Nouveau technicien</h1><p>Ajoutez les informations du technicien.</p></div></div>
<section class="panel form-panel"><form method="POST" action="{{ route('techniciens.store') }}">@include('techniciens.form', ['submitLabel' => 'Créer le technicien'])</form></section>
@endsection