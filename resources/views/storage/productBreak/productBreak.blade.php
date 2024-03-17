@extends('layouts.menu')
@section('content')
    <div class="d-flex justify-content-center">
        <div class="card" style="width: 40rem;">
            <div class="card-body">
                <label for="productId">Cikkszám:</label>
                <input id="productId" class="form-control border-dark" name="productId" value="{{isset($product) ? $product->productId.'-'.$product->index : ''}}" autocomplete="off" required>
                <form action="/storage/productBreak/addRow" method="post">
                    @csrf
                    <label for="oldStoragePlace">Raktárhelység neve:</label>
                    <input id="oldStoragePlace" class="form-control border-dark" name="oldStoragePlace" value="{{isset($product) ? $product->storagePlace : ''}}" disabled>
                    <label for="howMany">Darabszám:</label>
                    <input id="howMany" class="form-control border-dark" name="howMany" value="{{isset($product) ? $product->howMany : ''}}" disabled>
                    <label for="selectedQuantity">Szétbontott mennyiség:</label>
                    <input type="number" id="selectedQuantity" class="form-control border-dark" name="selectedQuantity" max="{{isset($product) ? ($product->howMany-1) : ''}}" value="" autocomplete="off" required>
                    <label for="newStoragePlace">Raktári helye:</label>
                    <input id="newStoragePlace" class="form-control border-dark" name="newStoragePlace" value="" autocomplete="off" required>
                    @if(isset($product))
                        <input type="hidden" name="brokenRowId" value="{{$product->id}}">
                        <input type="hidden" name="brokenProductId" value="{{$product->productId}}">
                        <input type="hidden" name="brokenIndex" value="{{$product->index}}">
                        <input type="hidden" name="oldStoragePlace" value="{{$product->storagePlace}}">
                    @endif
                    <div class="d-flex justify-content-end pt-3">
                        <a href="/storage/productBreak/getProduct" class="btn btn-danger mx-2">Törlés</a>
                        <input type="submit" class="btn btn-primary" value="Tétel bontás">
                    </div>
                </form>
                <script>
                    const productIdInput = document.getElementById('productId');
                    productIdInput.addEventListener('keypress', function (event) {
                        if (event.key === 'Enter') {
                            window.location.href = '/storage/productBreak/getProduct/' + productIdInput.value;
                        }
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
