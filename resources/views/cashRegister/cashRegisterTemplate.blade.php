@extends('layouts/menu')
@section('content')
    <div class="container-fluid">
        <div class="row bg-dark m-3 p-4">
            <div class="col-md-8 bg-white border border-dark border-2 rounded overflow-auto"  style="height: 400px">
                @yield('mainSpace')
            </div>
            <div class="col-md-4 bg-white border border-dark border-2 rounded">
                <div class="row pt-2 pb-2">
                    <div class="col-md-2">
                        <a href="/cashRegister" class="btn btn-sm"><img width="150%" src="{{asset('iconsAndLogos/cashRegisterLogo.png')}}"></a>
                    </div>
                    <div class="col-md-2">
                        <a href="cashRegister/productList" class="btn btn-sm"><img width="100%" src="{{asset('iconsAndLogos/searchIcon.png')}}"></a>
                    </div>
                </div>
                @yield('buttons')
            </div>
            @yield('other')
        </div>
    </div>
@endsection()
