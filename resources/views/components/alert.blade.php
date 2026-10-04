@props(['type' => 'info'])

<div {{ $attributes->merge(['class' => 'alert alert-' . $type, 'role' => 'status']) }}>
    {{ $slot }}
</div>
