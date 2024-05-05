@extends('layouts.menu')
@section('content')
    <div class="m-3 p-3 border border-dark rounded-3 bg-white">
        <div class="text-center pb-3">
            <button onclick="window.location.href = '/storage/menu'" class="btn button-red">Vissza a menübe</button>
        </div>
        <div class="d-flex table-responsive justify-content-center">
            <table class="table border border-dark">
                <thead class="table-dark">
                    <tr>
                        <td>Cikkszám</td>
                        <td>Termék neve</td>
                        <td>Mennyiség</td>
                        <td>Raktári helye</td>
                    </tr>
                </thead>
                <tbody>
                @if(count($products) > 0)
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->productId}}-{{$product->index}}</td>
                            <td>{{$product->productName}}</td>
                            <td>{{$product->howMany}} db</td>
                            <td>
                                <input class="form-control border-dark" type="text" id="{{$product->productId}}-{{$product->index}}" name="assignedStoragePlace">
                            </td>
                        </tr>
                        <script>
                            const productStorageInput = document.getElementById('{{$product->productId}}-{{$product->index}}');
                            productStorageInput.addEventListener('keypress', function (event) {
                                if (event.key === 'Enter') {
                                    window.location.href = '/storage/assignProduct/' + productStorageInput.id + '/' + productStorageInput.value
                                }
                            });
                        </script>
                    @endforeach
                @else
                    <tr class="align-middle">
                        <td colspan="4"><h1 class="text-center mx-auto p-5">Nincsenek olyan termékek, amelyek elhelyezésre várnak a raktárban!</h1></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
