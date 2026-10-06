<form method="POST" action="{{ $action }}" class="contact-form" style="max-width:620px">@csrf @if($method !== 'POST') @method($method) @endif
<label>Nom<input name="name" value="{{ old('name', $user?->name) }}" required></label>
<label>Email<input type="email" name="email" value="{{ old('email', $user?->email) }}" required></label>
<label>Rôle<select name="role" required>@foreach(['admin'=>'Administrateur','manager'=>'Gestionnaire','citizen'=>'Citoyen'] as $value => $label)<option value="{{ $value }}" @selected(old('role', $user?->role) === $value)>{{ $label }}</option>@endforeach</select></label>
<label>Mot de passe @if($user)<small>(laisser vide pour conserver)</small>@endif<input type="password" name="password" @required(!$user)></label>
<label>Confirmation<input type="password" name="password_confirmation" @required(!$user)></label>
<div><button class="button" type="submit">Enregistrer</button> <a class="button button-outline" href="{{ route('admin.users.index') }}">Annuler</a></div></form>
