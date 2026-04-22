@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-medium text-dark']) }}>
    {{ $value ?? $slot }}
</label>
