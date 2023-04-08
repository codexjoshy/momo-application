@php
$lastPay = \App\Models\MomoSchedule::query()->select('disbursed_amount')->latest()->value('disbursed_amount');
$balance = 40000;
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title')</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

    <style>
        @media print {
          .no-print {
            display: none !important;
          }
        }
    </style>

    @stack('css')
</head>

<body class="nav-fixed">
    <div class="overlay"></div>
    <!-- Navbar -->
    @include('partials.nav')
    <!-- Sidebar -->
    <div id="layoutSidenav">
        @include('partials.aside')
        <div id="layoutSidenav_content">
            <main>
                <!-- Main content-->
                <div class="container mt-4">
                    @yield('heading')
                    @if(session('success'))
                    <x-base.alert type="primary" title="Success" icon="fa-check">
                        {{ session('success') }}
                    </x-base.alert>
                    @endif

                    @if(session('error'))
                    <x-base.alert type="danger" title="Error" icon="fa-times">
                        {{ session('error') }}

                    </x-base.alert>
                    @endif
                    @if(session('errors'))
                    <x-base.alert type="danger" title="Errors" icon="fa-times">
                        <ul>
                            @foreach(session('errors') as $error)
                            <li>{{ $error }}</li>

                            @endforeach
                        </ul>
                    </x-base.alert>
                    @endif
                    @can('admin')
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Momo Balance</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">&#8358; {{ number_format($balance) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-bank fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Last Payment</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">&#8358;{{ number_format($lastPay) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-money fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        @yield('metrics')

                    </div>
                    @endcan
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function turnOnOverlay() {
            $('.overlay').css('display', 'block');
        }

        function turnOffOverlay() {
            $('.overlay').css('display', 'none');
        }
    </script>
    @stack('scripts')
</body>

</html>
