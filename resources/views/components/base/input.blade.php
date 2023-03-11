@props(['disabled' => false])

@php $name = $attributes->get('name'); @endphp

<input
    {!! $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) !!}
    {{ $disabled ? 'disabled' : '' }}
>
@error($name)
    <span class="invalid-feedback">
        {{ $message }}
    </span>
@enderror
