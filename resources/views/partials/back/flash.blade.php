@php
    $icons = [
        'success' => '✓',
        'error' => '✕',
        'warning' => '⚠',
        'info' => 'ℹ',
    ];
@endphp

@foreach (['success', 'error', 'warning', 'info'] as $type)
    @if (session($type))
        <div class="flash-message flash-{{ $type }}" role="status">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <span class="flash-icon" style="font-size: 16px; font-weight: 800; line-height: 1;">{{ $icons[$type] }}</span>
                <div style="flex: 1;">
                    <strong style="display: block; font-size: 13px; margin-bottom: 2px;">
                        {{ $type === 'success' ? 'Opération réussie' : ($type === 'error' ? 'Erreur rencontrée' : ($type === 'warning' ? 'Attention' : 'Information')) }}
                    </strong>
                    <div style="font-size: 13px; line-height: 1.5;">{{ session($type) }}</div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="flash-message flash-error" role="alert">
        <div style="display: flex; align-items: flex-start; gap: 12px;">
            <span class="flash-icon" style="font-size: 16px; font-weight: 800; line-height: 1;">✕</span>
            <div style="flex: 1;">
                <strong style="display: block; font-size: 13px; margin-bottom: 4px;">
                    Formulaire incomplet ou incohérent ({{ $errors->count() }} {{ $errors->count() > 1 ? 'anomalies détectées' : 'anomalie détectée' }}) :
                </strong>
                <ul style="margin: 4px 0 0; padding-left: 18px; font-size: 12.5px; line-height: 1.6;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
