@extends('settings.settingsTemplate')
@section('settingsBody')
    <form method="POST" action="/settings/newEmployee">
        @csrf
        <div class="row">
            <div class="col-md-6">
                Vezetéknév:
                <input id="firstName" type="text"
                       class="form-control @error('firstName') is-invalid @enderror"
                       name="firstName" value="{{ old('firstName') }}" required
                       autocomplete="firstName" autofocus>
                @error('firstName')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-6">
                Keresztnév:
                <input id="lastName" type="text"
                       class="form-control @error('lastName') is-invalid @enderror" name="lastName"
                       value="{{ old('lastName') }}" required autocomplete="lastName">
                @error('lastName')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-6">
                Telefonszám:
                <input id="phoneNumber" type="text"
                       class="form-control @error('phoneNumber') is-invalid @enderror"
                       name="phoneNumber" value="{{ old('phoneNumber') }}" required
                       autocomplete="phoneNumber">
                @error('phoneNumber')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-6">
                Pozíció:
                <select class="form-control" name="position" required>
                    <option value="">...</option>
                    <option value="admin">Admin</option>
                    <option value="both">Mindkettő</option>
                    <option value="storage">Raktáros</option>
                    <option value="cashier">Pénztáros</option>
                </select>
                @error('position')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-0 pt-3">
            <div class="col-md-12">
                <a class="btn btn-danger me-2" href="#">
                    Mégsem
                </a>
                <button type="submit" class="btn btn-primary">
                    Felvétel
                </button>
            </div>
        </div>
    </form>
@endsection
