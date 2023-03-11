@props(['href', 'title' => ''])

<nav class="sidenav-menu-nested nav accordion" id="side-nav-pages">
    <a class="nav-link" href="{{ $href }}">
        {{ $title }}
    </a>
</nav>
