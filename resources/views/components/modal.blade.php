@props(['id'])

<dialog id="{{ $id }}" {{ $attributes->merge(['class' => 'modal']) }}>
    {{ $slot }}
</dialog>
