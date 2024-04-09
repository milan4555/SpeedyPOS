@extends('layouts.menu')
@section('content')
    <meta http-equiv="refresh" content="15" />
    <div class="m-3 p-3 border border-dark rounded-3 bg-white">
        <table class="table">
            <thead>
                <tr class="border border-dark">
                    <td>
                        <input id="productIdOrder" class="form-control border-dark" placeholder="Cikkszám helye" autofocus>
                    </td>
                    <td colspan="5">
                        <div class="d-flex justify-content-end">
                            <a href="/storage/productOut/finishOrder/{{$orderNumber}}" class="btn btn-primary" {{$howManyNotZero != 0 ? 'disabled' : ''}}>Befejezés</a>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#productOutRestoreProgress" class="btn btn-warning mx-2" {{$sameRowCount == count($orderItems) ? 'disabled' : ''}}>Újrakezdés</button>
                            <a href="/storage/menu" class="btn btn-danger">Vissza a menübe</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Cikkszám</th>
                    <th>Termék megnevezés</th>
                    <th>Raktár darabszám</th>
                    <th>Rendelt</th>
                    <th>Raktári helye</th>
                    <th>Állapot</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $orderItem)
                    @php($storagePlaceInfo = \App\Http\Controllers\ProductOutController::getBestStoragePlaceInfo($orderItem->productId))
                    <tr>
                        <td>{{$storagePlaceInfo->productId}}-{{$storagePlaceInfo->index}}</td>
                        <td>{{$storagePlaceInfo->productName}}</td>
                        <td>{{$storagePlaceInfo->howMany}} db</td>
                        <td>{{$orderItem->howManyLeft}} db</td>
                        <td>{{$storagePlaceInfo->storagePlace}}</td>
                        <td class="{{$orderItem->howManyLeft == 0 ? 'bg-success' : 'bg-danger'}}"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('storage.modals._productOutRestoreProgress')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const productIdInput = document.getElementById('productIdOrder');
        productIdInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                window.location.href = '/storage/productOut/orders/' + {{$orderNumber}} + '/' + productIdInput.value;
            }
        })
    </script>
@endsection
