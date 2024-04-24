@extends('layouts.menu')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border border-2 border-dark">
                    <div class="card-header bg-dark text-white">Bejelentkezés</div>
                    <div class="card-body">
                        <form method="POST" action="/login">
                            @csrf
                            <div class="row mb-3">
                                <label for="username" class="col-md-4 col-form-label text-md-end">Felhasználónév:</label>
                                <div class="col-md-6">
                                    <input id="username" type="text"
                                           class="form-control border-dark" name="username"
                                           value="{{ old('username') }}" required autocomplete="username" autofocus>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Jelszó:</label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control border-dark" name="password"
                                           required autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-5">
                                    <button type="submit" class="btn btn-primary">
                                        Bejelentkezés
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
