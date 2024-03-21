@extends('layouts.menu')
@section('content')
    <div class="m-3 p-3 border border-dark rounded-3 bg-white">
        <div class="text-center pb-3">
            <h3>Elrakatlan termékek listája:</h3>
        </div>
        <div class="d-flex justify-content-center">
            <table class="table">
                <thead>
                    <tr>
                        <td>Cikkszám</td>
                        <td>Termék neve</td>
                        <td>Mennyiség</td>
                        <td>Raktári helye</td>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
    </div>
@endsection
