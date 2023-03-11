@props([
    'for' => '',
    'label' => '',
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    <label for="{{ $for }}" class="form-label">
        {{ $label }} {!! ($required) ? "<span class='text-danger'>*</span>" : '' !!}
    </label>
    {{ $slot }}
</div>
