@props(['type' => 'info', 'title' => '', 'icon' => 'fa-info'])

<div class="alert alert-{{ $type }} alert-icon" role="alert">
    <button class="close" type="button" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <div class="alert-icon-aside">
        <i class="fa {{ $icon }}"></i>
    </div>
    <div class="alert-icon-content">
        <h6 class="alert-heading">{{ $title }}</h6>
        {{ $slot }}
    </div>
</div>
