@extends('layouts.back')
@section('content')
<div class="content-heading"><div><span class="eyebrow">RESSOURCES</span><h1>Nouveau financement</h1><p>Associez une ressource financière à un projet.</p></div></div>
<section class="panel form-panel"><form method="POST" action="{{ route('financements.store') }}">@include('financements.form', ['submitLabel' => 'Créer le financement'])</form></section>
@endsection
