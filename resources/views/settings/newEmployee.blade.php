@extends('settings.settingsTemplate')
@section('settingsBody')
    <form method="POST" action="/settings/newEmployee">
        @csrf
        <div class="row">
            <div class="col-md-6">
                Vezetéknév:
                <input id="firstName" type="text"
                       class="form-control border-dark"
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
                       class="form-control border-dark" name="lastName"
                       value="{{ old('lastName') }}" required autocomplete="lastName">
                @error('lastName')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-6">
                Telefonszám:
                <input id="phoneNumber" type="number"
                       class="form-control border-dark"
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
                <select class="form-control border-dark" name="position" required>
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
        <div class="d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary">
                Felvétel
            </button>
        </div>
    </form>
@endsection
