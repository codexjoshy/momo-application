@props(['href', 'title' => ''])

<a class="nav-link {{ ($href === url()->current()) ? 'active' : '' }}" href="{{ $href }}">
    <div class="nav-link-icon">
        {{ $icon ?? '' }}
    </div>
    {{ $title }}
</a>
