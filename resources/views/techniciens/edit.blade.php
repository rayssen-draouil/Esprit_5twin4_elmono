@extends('layouts.back')
@section('content')
<div class="content-heading"><div><span class="eyebrow">ÉQUIPES</span><h1>Modifier le technicien</h1><p>{{ $technician->name }}</p></div></div>
<section class="panel form-panel"><form method="POST" action="{{ route('techniciens.update', $technician) }}">@method('PUT') @include('techniciens.form', ['submitLabel' => 'Enregistrer'])</form></section>
@endsection