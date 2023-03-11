@props(['disabled' => false, 'placeholder' => '', 'readonly' => false])

@php $name = $attributes->get('name'); @endphp

<select
    {!! $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) !!}
    {{ $disabled ? 'disabled' : '' }}
    {{ $readonly ? 'readonly' : '' }}
>
    @if (!empty($placeholder))
        <option value="">{{ $placeholder }}</option>
    @endif
    {{ $slot }}
</select>

@error($name)
    <span class="invalid-feedback">
        {{ $message }}
    </span>
@enderror
