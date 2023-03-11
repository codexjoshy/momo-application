@props(['key'])
@cannot('auditor')
<button {{ $attributes->merge(['class' => 'btn']) }} type="button" data-toggle="modal" data-target="#{{ $key }}">
    {{ $slot }}
</button>

@endcannot
