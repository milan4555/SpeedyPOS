@extends('layouts/menu')
@section('content')
    <div class="container-fluid">
        <div class="row bg-dark m-3 p-4 rounded-5">
            <div class="col-md-8 bg-white rounded overflow-auto cash-register-main-space" style="height: 480px; padding: 0;">
                @yield('mainSpace')
            </div>
            <div class="col-md-4 bg-white border border-dark border-2 rounded">
                <div class="row">
                    <div class="col-md-3 cash-register-button">
                        <a href="/cashRegister" class="btn w-100"><img class="mx-auto" width="80%" src="{{asset('svgFiles/cashRegisterIcon.svg')}}"></a>
                    </div>
                    <div class="col-md-3 cash-register-button">
                        <a href="/cashRegister/productList" class="btn w-100"><img width="80%" src="{{asset('svgFiles/searchIcon.svg')}}"></a>
                    </div>
                    <div class="col-md-3 cash-register-button">
                        <a href="/cashRegister/companyList" class="btn w-100"><img width="80%" src="{{asset('svgFiles/companyIcon.svg')}}"></a>
                    </div>
                    <div class="col-md-3 cash-register-button">
                        <a href="/cashRegister/receiptList" class="btn w-100"><img width="80%" src="{{asset('svgFiles/receiptIcon.svg')}}"></a>
                    </div>
                </div>
                @yield('buttons')
            </div>
            @yield('other')
        </div>
    </div>
@endsection()
