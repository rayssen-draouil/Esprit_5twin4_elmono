@extends('layouts.back')
@section('content')<div class="content-heading"><div><span class="eyebrow">ADMINISTRATION</span><h1>Modifier {{ $user->name }}</h1></div></div><section class="panel" style="padding:28px">@include('users.form', ['action' => route('admin.users.update', $user), 'method' => 'PUT'])</section>@endsection
