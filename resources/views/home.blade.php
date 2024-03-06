@extends('layouts.menu')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white text-center"><h5>Válaszd ki, hogy melyik rendszert szeretnéd használni!</h5></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="card" style="width: 18rem; height: 449px">
                                        <img class="card-img-top" width="10%" src="{{asset('iconsAndLogos/cashRegisterLogo.png')}}" alt="Card image cap">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">Kassza rendszer</h5>
                                            <p class="card-text">Termékek eladása, számlák írása, termékinformációk, stb.</p>
                                            <a href="/cashRegister" class="btn btn-warning mt-auto">Kiválasztás</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-5">
                                    <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="{{asset('iconsAndLogos/wareHouseLogo.png')}}" alt="Card image cap">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">Raktárkezelő</h5>
                                            <p class="card-text">Raktárkészlet kezelése, árufelvétel, árutasítás, stb.</p>
                                            <a href="/storage/menu" class="btn btn-warning mt-auto">Kiválasztás</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="/settings/variables" class="btn btn-sm btn-primary m-2">Beállítások</a>
                </div>
            </div>
        </div>
    </div>
@endsection
