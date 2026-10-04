@props(['label', 'tone' => 'neutral'])

<span {{ $attributes->merge(['class' => 'status-badge status-' . $tone]) }}>{{ $label }}</span>
