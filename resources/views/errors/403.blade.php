@extends('layouts.auth', ['title' => 'Accès refusé', 'heading' => '403 · Accès refusé', 'subtitle' => 'Vous n’avez pas les droits nécessaires pour accéder à cette page.'])
@section('content')<a class="button" href="{{ Auth::check() ? route('dashboard') : route('home') }}">Retour</a>@endsection
