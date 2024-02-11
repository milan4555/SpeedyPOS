@extends('layouts.menu')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white text-center">
                        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                <div class="navbar-nav">
                                    {{request()->is()}}
                                    <a class="nav-item nav-link {{request()->is('settings/variables') ? 'active' : ''}}" href="/settings/variables">Adatok</a>
                                    <a class="nav-item nav-link {{request()->is('settings/newEmployee') ? 'active' : ''}}" href="/settings/newEmployee">Dolgozó felvétele</a>
                                    <a class="nav-item nav-link {{request()->is('settings/userRights') ? 'active' : ''}}" href="/settings/userRights">Jogosultságok</a>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="card-body">
                       @yield('settingsBody')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
