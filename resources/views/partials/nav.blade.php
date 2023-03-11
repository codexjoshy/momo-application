<nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
    <a class="navbar-brand" href="">
        {{ config('app.name', 'Laravel') }}
        {{-- <img src="{{url('/images/transparent.png')}}" /> --}}
    </a>
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle"
        href="javascript:void(0)">
        <i class="fa fa-bars"></i>
    </button>
    <ul class="navbar-nav align-items-center ml-auto">
        {{-- <x-notifications /> --}}
        <x-profile-dropdown />
    </ul>

</nav>
