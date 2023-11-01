<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        html, body {
            height:100%;
        }
        body {
            height:100%;
            background-image: linear-gradient(to bottom right, lightgray, teal);
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-dark text-white align-middle">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand" href="#">
            </a>
            <!-- Left links -->
            <ul class="navbar-nav">
                <img
                    src="{{asset('iconsAndLogos/littleLogoRemoved.png')}}"
                    width="20%"
                    alt="Logo"
                    loading="lazy"
                />
                <li><h3 class="pt-2">SpeedyPOS</h3></li>
            </ul>
            @guest
            @else
                <ul class="navbar-nav ms-auto">
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Kijelentkezés</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            @endguest
        </div>
    </div>
</nav>
<main class="py-4">
    @yield('content')
</main>
</body>
</html>
