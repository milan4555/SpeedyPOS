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
    <!-- Selectpicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <!-- Toogle button -->
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/dist/bootstrap-switch-button.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/css/bootstrap-switch-button.min.css" rel="stylesheet">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        html, body {
            height:100%;
        }
        body {
            background: #4a5568;
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
                <ul class="navbar-nav ms-auto pt-2">
                    <li>
                        <p>(Belépve: {{\Illuminate\Support\Facades\Auth::getUser()['firstName']}} {{\Illuminate\Support\Facades\Auth::getUser()['lastName']}})</p>
                    </li>
                    <li>
                        <a class="dropdown-item px-2" href="/home" >Vissza a főmenübe</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/logout"
                           onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Kijelentkezés</a>
                        <form id="logout-form" action="/logout" method="POST" class="d-none">
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
