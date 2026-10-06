@extends('layouts.back')
@section('content')
<div class="content-heading"><div><span class="eyebrow">PORTFOLIO</span><h1>Modifier le projet</h1><p>Mettez à jour les informations de {{ $project->name }}.</p></div></div>
<section class="panel form-panel"><form method="POST" action="{{ route('projects.update', $project) }}">@method('PUT')@include('projects.form', ['submitLabel' => 'Enregistrer les modifications'])</form></section>
@endsection
