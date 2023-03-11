@props(['title' => '', 'key'])

<a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#collapse-{{ $key }}"
    aria-expanded="false" aria-controls="collapse-{{ $key }}">
    <div class="nav-link-icon">
        {{ $icon ?? '' }}
    </div>
    {{ $title }}
    <div class="sidenav-collapse-arrow">
        <i class="fa fa-angle-down"></i>
    </div>
</a>
<div class="collapse" id="collapse-{{ $key }}" data-parent="#side-nav">
    {{ $slot }}
</div>
