@extends('layouts/menu')
@section('content')
    <div class="container-fluid">
        <div class="row bg-dark m-3 p-4">
            <div class="col-md-8 bg-white border border-dark border-2 rounded overflow-auto"  style="height: 400px">
                @yield('mainSpace')
            </div>
            <div class="col-md-4 bg-white border border-dark border-2 rounded">
                <button type="button" class="btn btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#emptyCashRegisterModal">Megszakítás</button>
                @include('cashRegister\modals\_emptyCashRegisterModal')
                <div class="row">
                    <div class="col-md-6">
                        <a type="button" class="btn btn-primary w-100 mt-2" href="/cashRegister/makeReceipt/K">Kártyás fizetés</a>
                    </div>
                    <div class="col-md-6">
                        <a type="button" class="btn btn-primary w-100 mt-2" href="/cashRegister/makeReceipt/B">Bankártyás fizetés</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 bg-white border border-dark border-2 rounded">
                <form action="{{url('/cashRegister.blade.php')}}" method="post">
                    @csrf
                    <input type="number" name="lastProductId" class="form-control w-100 h-100" autocomplete="off" autofocus>
                </form>
            </div>
            <div class="col-md-2 bg-white border border-dark border-2 rounded">
                <h5 class="mt-1">Teljes összeg: {{$sumPrice}} Ft.</h5>
            </div>
        </div>
    </div>
@endsection()
