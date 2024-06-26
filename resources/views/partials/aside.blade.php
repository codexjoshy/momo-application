<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="side-nav">
                <div class="sidenav-menu-heading">Admin</div>
                @can('admin')
                {{-- <x-aside-dropdown title="Disbursement" key="disbursement">
                    <x-slot name="icon">
                        <i class="fa fa-book"></i>
                    </x-slot>
                    <x-aside-link :href="route('admin.schedule.index')" title="Schedules">
                        <x-slot name="icon">
                            <i class="fa fa-table"></i>
                        </x-slot>
                    </x-aside-link>
                    <x-aside-link :href="route('admin.schedule.create')" title="Upload">
                        <x-slot name="icon">
                            <i class="fa fa-upload"></i>
                        </x-slot>
                    </x-aside-link>

                </x-aside-dropdown> --}}
                <x-aside-link :href="route('admin.home')" title="Dashboard">
                    <x-slot name="icon">
                        <i class="fa fa-home"></i>
                    </x-slot>
                </x-aside-link>
                <x-aside-dropdown title="Airtime" key="airtime">
                    <x-slot name="icon">
                        <i class="fa fa-cloud"></i>
                    </x-slot>
                    <x-aside-link :href="route('admin.feature.schedule.index', ['type'=>'airtime'])" title="Schedules">
                        <x-slot name="icon">
                            <i class="fa fa-table"></i>
                        </x-slot>
                    </x-aside-link>
                    <x-aside-link :href="route('admin.feature.schedule.create', ['type'=>'airtime'])" title="Upload">
                        <x-slot name="icon">
                            <i class="fa fa-upload"></i>
                        </x-slot>
                    </x-aside-link>

                </x-aside-dropdown>
                @endcan
            </div>
        </div>
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle"></div>
                <div class="sidenav-footer-title">
                </div>
            </div>
        </div>
    </nav>
</div>