@extends('layouts.back')
@section('content')
<div class="content-heading"><div><span class="eyebrow">PORTFOLIO</span><h1>Nouveau projet</h1><p>Ajoutez un projet au portefeuille AquaSecure.</p></div></div>
<section class="panel form-panel"><form method="POST" action="{{ route('projects.store') }}" novalidate id="form_project" class="validated-form">@include('projects.form', ['submitLabel' => 'Créer le projet'])</form></section>
@endsection
