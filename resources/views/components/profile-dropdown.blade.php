<li class="nav-item dropdown no-caret mr-2 dropdown-user">
    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);"
        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="img-fluid" src="{{ Storage::url('avatar.png' ) }}" />
    </a>
    <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up"
        aria-labelledby="navbarDropdownUserImage">
        <h6 class="dropdown-header d-flex align-items-center">
            <img class="dropdown-user-img" src="{{ Storage::url("avatar.png") }}" />
            <div class="dropdown-user-details">
                <div class="dropdown-user-details-name">{{optional(auth()->user())->name }}</div>
                <div class="dropdown-user-details-email">{{optional( auth()->user())->email }}</div>
            </div>
        </h6>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#!">
            <div class="dropdown-item-icon">
                <i class="fa fa-cog"></i>
            </div>
            Account
        </a>
        <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="dropdown-item-icon"><i class="fa fa-sign-out"></i></div>
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</li>
