@extends('layouts.menu')
@section('content')
    <meta http-equiv="refresh" content="15"/>
    <div class="m-3 p-3 border border-dark rounded-3 bg-white">
        <table class="table">
            <thead>
            <tr class="border border-dark">
                <td colspan="2">
                    <form action="/storage/productOut/forStore/addProductToList" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-7">
                                <input name="productIdOrder" class="form-control border-dark" placeholder="Cikkszám helye" autofocus>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="howManyOrder" class="form-control border-dark" placeholder="Darabszám" autofocus>
                            </div>
                            <div class="col-md-2">
                                <input class="btn btn-primary" type="submit" value="Felírás">
                            </div>
                        </div>
                    </form>
                </td>
                <td colspan="4">
                    <div class="d-flex justify-content-end">
                        <a href="/storage/productOut/orders/-1" class="btn btn-primary">Váltás kiadásra</a>
                        <a href="/storage/productOut/forStore/restart" class="btn btn-warning mx-2">Újrakezdés</a>
                        <a href="/storage/menu" class="btn btn-danger">Vissza a menübe</a>
                    </div>
                </td>
            </tr>
            @if(isset($orderItems))
            <tr>
                <th>Cikkszám</th>
                <th>Termék megnevezés</th>
                <th>Rendelt</th>
                <th>Műveletek</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $orderItem)
                    @php($storagePlaceInfo = \App\Http\Controllers\ProductOutController::getBestStoragePlaceInfo($orderItem->productId))
                    <tr>
                        <td>{{$storagePlaceInfo->productId}}</td>
                        <td>{{$storagePlaceInfo->productName}}</td>
                        <td>{{$orderItem->howMany}} db</td>
                        <td id="{{$orderItem->id}}">
                            <button class="btn btn-primary btn-sm" onclick="quantityDiff({{$orderItem->id}})">Eltérő mennyiség</button>
                            <a href="/storage/productOut/forStore/remove/{{$orderItem->id}}" class="btn btn-danger btn-sm">Törlés</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            @endif
        </table>
    </div>
    <script>
        function quantityDiff(rowId) {
            const tableColumn = document.getElementById(rowId)
            tableColumn.innerHTML = "<div class='col-auto'>" +
                "<input id='quantityInput" + rowId + "' class='form-control-sm border-dark' type='number' placeholder='Mennyiség' style='width: auto;'>" +
                "<button class='btn btn-sm' onclick='updateRow(" + rowId + ")'>✅</button>" +
                "<button class='btn text-danger btn-sm' onclick='getButtonBack(" + rowId + ")'>X</button>" +
                "</div>"
        }
        function updateRow(rowId) {
            const quantity = document.getElementById('quantityInput' + rowId).value;
            window.location.href = '/storage/productOut/forStore/update/' + rowId + '/' + quantity;
        }
        function getButtonBack(rowId) {
            console.log(rowId);
            const tableColumn = document.getElementById(rowId)
            tableColumn.innerHTML = "<button class='btn btn-primary btn-sm mx-1' onclick='quantityDiff(" + rowId + ")'>Eltérő mennyiség</button>" +
                "<a href='/storage/productOut/forStore/remove/" + rowId + "' class='btn btn-danger btn-sm'>Törlés</a>"
        }
    </script>
{{--    @include('storage.modals._productOutRestoreProgress')--}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
