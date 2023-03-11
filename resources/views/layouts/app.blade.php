<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}" />

    {{-- <script data-search-pseudo-elements defer
        src="../../cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="../../cdnjs.cloudflare.com/ajax/libs/feather-icons/4.27.0/feather.min.js" crossorigin="anonymous">
    </script> --}}
</head>

<body class="LogBody">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div style='height: 100px;' class="w-100 text-center bg-light pt-2">
                    <img width="100" height="100" class="img-fluid" src='{{ asset('images/ciin.png') }}' />
                </div>
                <h4 class="text-center"></h4>
                @yield('content')
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer mt-auto footer-dark">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 text-md-right small">
                            &#xB7;
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- Developed by Alabian Solutions Limited
    Phone: 08034265103
    Email: info@alabiansolutions.com
    Lead Developer: Alabi A. (facebook.com/alabi.adebayo)
    Developer : Joshua E. (facebook.com/ebhoria.joshua)
    -->
    </div>
    <script src="{{ asset('js/jquery.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" crossorigin="anonymous">
    </script>
    {{-- <script src="{{ asset('js/style.js') }}"></script> --}}
</body>



</html>
