@extends('layouts.back')
@section('content')
<div class="content-heading"><div><span class="eyebrow">RESSOURCES</span><h1>Modifier le financement</h1><p>Mettez à jour cette ressource financière.</p></div></div>
<section class="panel form-panel"><form method="POST" action="{{ route('financements.update', $financement) }}">@method('PUT')@include('financements.form', ['submitLabel' => 'Enregistrer les modifications'])</form></section>
@endsection
