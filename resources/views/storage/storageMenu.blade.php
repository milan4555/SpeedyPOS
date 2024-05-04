@extends('layouts.menu')
@section('content')
    <div class="d-flex justify-content-center">
        <div class="row my-3">
            <div class="col-md-3 "></div>
            <div onclick="location.href='/storage/productsList';" class="col-md-3 storage-menu-buttons corner-top-left"><h4 class="text-center my-3">Termékek listája</h4></div>
            <div onclick="location.href='/storage/storageUnits/0';" class="col-md-3 storage-menu-buttons corner-top-right"><h4 class="text-center my-3">Raktárhelységek</h4></div>
            <div class="col-md-3"></div>

            <div class="col-md-3 "></div>
            <div onclick="location.href='/storage/productIn';" class="col-md-3 storage-menu-buttons"><h4 class="text-center my-3">Termék bevétel</h4></div>
            <div onclick="location.href='/storage/productOut/selector';" class="col-md-3 storage-menu-buttons"><h4 class="text-center my-3">Termék kiadás</h4></div>
            <div class="col-md-3"></div>

            <div class="col-md-3 "></div>
            <div onclick="location.href='/storage/unassignedProducts';" class="col-md-3 storage-menu-buttons"><h4 class="text-center my-3">Elhelyezetlen tételek</h4></div>
            <div onclick="location.href='/storage/productBreak/getProduct';" class="col-md-3 storage-menu-buttons"><h4 class="text-center my-3">Tétel mozgatás/bontás</h4></div>
            <div class="col-md-3"></div>

            <div class="col-md-3 "></div>
            <div onclick="location.href='/storage/inventory/0';" class="col-md-3 storage-menu-buttons"><h4 class="text-center my-3">Leltározás</h4></div>
            <div onclick="location.href='/storage/riportPage';" class="col-md-3 storage-menu-buttons"><h4 class="text-center my-3">Riportok és analitika</h4></div>
            <div class="col-md-3"></div>

            <div class="col-md-3 "></div>
            <div onclick="location.href='/storage/documents/productIn';" class="col-md-3 storage-menu-buttons corner-bottom-left"><h4 class="text-center my-3">Dokumentumok</h4></div>
            <div onclick="location.href='/home';" class="col-md-3 bg-danger storage-menu-button-back corner-bottom-right"><h4 class="text-center my-3">Főmenü</h4></div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection
