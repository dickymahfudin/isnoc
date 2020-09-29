<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="icon" type="image/png" href="{{ asset('images/LogoSundaya.png') }}"> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div id="app">
        <span id="auth" auth="{{ Auth::user()->api_token }} "></span>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <img src="{{ asset('images/sundaya.png') }}" alt="SIA" style="height: 30px; padding: 0 auto;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto topnav">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                {{-- @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif --}}
                            @else
                                <li class="nav-item h5">
                                    <a class="nav-link {{ Request::is('home') ? 'bg-warning text-dark' : ''}}" href="{{route('noc')}}">NOC</a>
                                </li>

                                <li class="nav-item dropdown h5">
                                    <a class="nav-link dropdown-toggle {{ Request::is('nojs') ? 'bg-warning text-dark' : ''}} {{ Request::is('nojs/detail') ? 'bg-warning text-dark' : ''}} {{ Request::is('nojs/sla') ? 'bg-warning text-dark' : ''}} {{ Request::is('nojs/dataprocessing') ? 'bg-warning text-dark' : ''}} " href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Nojs</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item {{ Request::is('nojs') ? 'bg-warning text-dark' : ''}}" href="{{route('nojs.index')}}">Nojs</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item {{ Request::is('nojs/detail') ? 'bg-warning text-dark' : ''}}" href="{{route('nojs.detail')}}">Detail</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item {{ Request::is('nojs/dataprocessing') ? 'bg-warning text-dark' : ''}}" href="{{route('nojs.dataprocessing')}}">Data  Processing</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item {{ Request::is('nojs/sla') ? 'bg-warning text-dark' : ''}}" href="{{route('nojs.sla')}}">SLA</a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown h5">
                                    <a class="nav-link dropdown-toggle {{ Request::is('prtg/sla') ? 'bg-warning text-dark' : ''}} {{ Request::is('prtg/state') ? 'bg-warning text-dark' : ''}}" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PRTG</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item {{ Request::is('prtg/sla') ? 'bg-warning text-dark' : ''}}" href="{{route('sla.prtg')}}">SLA</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item {{ Request::is('prtg/state') ? 'bg-warning text-dark' : ''}}" href="{{route('state.prtg')}}">State</a>
                                    </div>
                                </li>

                                <li class="nav-item h5">
                                    <a class="nav-link {{ Request::is('servicecalls') ? 'bg-warning text-dark' : ''}}" href="{{route('servicecalls')}}">Service Calls</a>
                                </li>

                                <li class="nav-item h5">
                                    <a class="nav-link {{ Request::is('material') ? 'bg-warning text-dark' : ''}}" href="{{route('material.index')}}">Material</a>
                                </li>

                                <li class="nav-item dropdown h5">
                                    <a class="nav-link dropdown-toggle {{ Request::is('other') ? 'bg-warning text-dark' : ''}} {{ Request::is('ajn') ? 'bg-warning text-dark' : ''}}" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Other</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item {{ Request::is('other') ? 'bg-warning text-dark' : ''}}" href="{{route('other.index')}}">Other</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item {{ Request::is('ajn') ? 'bg-warning text-dark' : ''}}" href="{{route('ajn.index')}}">AJN</a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown h5">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>

                                <li class="nav-item h5">
                                    <div class="nav-link clock active"></div>
                                </li>

                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-6">
            @yield('content')
        </main>
    </div>

    <script src="{{asset('vendor/jquery/jquery-3.4.1.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/popper.js') }}"></script>

    @stack('scripts')
    <script>
        $(document).ready(function () {
        setInterval(function () {
            function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
            }
            let days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            let clock = new Date();
            $('.clock').html(
                `${days[clock.getDay()]}, ${checkTime(clock.getHours())}:${checkTime(clock.getMinutes())}:${checkTime(clock.getSeconds())}`);
        }, 1000);
        });
    </script>

</body>
</html>
