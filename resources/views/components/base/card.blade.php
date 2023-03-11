@props(['title' => ''])

<div {{ $attributes->merge(['class' => 'card card-header-actions']) }}>
    <div class="card-header">
        {{ $title }}
        {{ $action ?? '' }}
    </div>


    <div class="card-body">
        {{ $slot }}
    </div>
</div>
