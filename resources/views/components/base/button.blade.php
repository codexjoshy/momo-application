@cannot('auditor')
<button {{ $attributes->merge(['class' => 'btn']) }}>
    {{ $slot }}
</button>

@endcannot
