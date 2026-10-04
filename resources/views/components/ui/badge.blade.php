@props(['variant' => 'info'])<span {{ $attributes->merge(['class' => 'badge badge-'.$variant]) }}>{{ $slot }}</span>
