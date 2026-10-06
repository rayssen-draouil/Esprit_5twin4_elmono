@extends('layouts.back')
@section('content')<div class="content-heading"><div><span class="eyebrow">ADMINISTRATION</span><h1>Ajouter un utilisateur</h1></div></div><section class="panel" style="padding:28px">@include('users.form', ['user' => null, 'action' => route('admin.users.store'), 'method' => 'POST'])</section>@endsection
