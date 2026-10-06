@foreach (['success', 'error', 'warning', 'info'] as $type)
    @if (session($type))
        <div class="flash-message flash-{{ $type }}" role="status">{{ session($type) }}</div>
    @endif
@endforeach

@if ($errors->any())
    <div class="flash-message flash-error" role="alert">
        <strong>Veuillez corriger les erreurs suivantes :</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
