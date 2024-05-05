@extends('layouts.menu')
@section('content')
    <div class="d-flex flex-column login-panel">
        <h1 class="text-center"><span>Speedy</span><span class="text-primary">POS</span></h1>
        <img
            src="{{asset('iconsAndLogos/littleLogoRemoved.png')}}"
            width="15%"
            alt="Logo"
            loading="lazy"
            class="bg-white rounded-circle border border-dark border-2 mx-auto"
        />
        <div class="d-flex flex-column">
            <form method="POST" action="/login">
                @csrf
                <input id="username" type="text"
                       class="form-control border-dark login-input-width mx-auto mt-3" name="username"
                       value="{{ old('username') }}" required autocomplete="username" placeholder="Felhasználónév" autofocus>
                <input id="password" type="password"
                       class="form-control border-dark login-input-width mx-auto mt-3" name="password"
                       required autocomplete="current-password" placeholder="Jelszó">
                <input type="submit" class="form-control button-blue mt-3 mx-auto login-button" value="Bejelentkezés">
            </form>
        </div>
    </div>
@endsection
