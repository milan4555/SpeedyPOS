@extends('layouts.menu')
@section('content')
    <div class="d-flex justify-content-center">
        <div class="card" style="width: 40rem;">
            <div class="card-body">
                <label for="productId">Cikkszám:</label>
                <input id="productId" class="form-control border-dark" name="productId"
                       value="{{isset($product) ? $product->productId.'-'.$product->index : ''}}" autocomplete="off"
                       required>
                <h6 class="pt-2">Művelet típusa?</h6>
                <div class="d-flex justify-content-start">
                    <div class="mx-2">
                        <input id="productMoveCheck" type="checkbox" name="productMoveCheck" value="productMove"
                               {{session()->get('actionInventory') === 'move' ? 'checked' : ''}}>
                        <label for="productMoveCheck">Termék mozgatás</label>
                    </div>
                    <div class="mx-2">
                        <input id="productBreakCheck" type="checkbox" name="productBreakCheck" value="productBreak">
                        <label for="productBreakCheck">Termék bontás</label>
                    </div>
                </div>
                <form id="productBreakOrMoveForm" action="/storage/productBreak/addRow" method="post">
                    @csrf
                    <label for="oldStoragePlace">Raktárhelység neve:</label>
                    <input id="oldStoragePlace" class="form-control border-dark" name="oldStoragePlace"
                           value="{{isset($product) ? $product->storagePlace : ''}}" disabled>
                    <label for="howMany">Darabszám:</label>
                    <input id="howMany" class="form-control border-dark" name="howMany"
                           value="{{isset($product) ? $product->howMany : ''}}" disabled>
                    <label for="selectedQuantity">Szétbontott mennyiség:</label>
                    <input type="number" id="selectedQuantity" class="form-control border-dark" name="selectedQuantity"
                           max="{{isset($product) ? ($product->howMany-1) : ''}}" value="" autocomplete="off" required>
                    <label for="newStoragePlace">Új raktári helye:</label>
                    <input id="newStoragePlace" class="form-control border-dark" name="newStoragePlace" value=""
                           autocomplete="off" required>
                    @if(isset($product))
                        <input type="hidden" name="brokenRowId" value="{{$product->id}}">
                        <input type="hidden" name="brokenProductId" value="{{$product->productId}}">
                        <input type="hidden" name="brokenIndex" value="{{$product->index}}">
                        <input type="hidden" name="oldStoragePlace" value="{{$product->storagePlace}}">
                    @endif
                    @if(session()->get('redirectStorageId') != null)
                        <input type="hidden" name="redirectStorageId" value="{{session()->get('redirectStorageId')}}">
                    @endif
                    <div class="d-flex justify-content-end pt-3">
                        <a href="/storage/productBreak/getProduct" class="btn btn-danger mx-2">Törlés</a>
                        <input id="submitButton" type="submit" class="btn btn-primary" value="Tétel ???">
                    </div>
                </form>
                <script>
                    const productIdInput = document.getElementById('productId');
                    productIdInput.addEventListener('keypress', function (event) {
                        if (event.key === 'Enter') {
                            window.location.href = '/storage/productBreak/getProduct/' + productIdInput.value;
                        }
                    });
                    const productMove = document.getElementById('productMoveCheck');
                    const productBreak = document.getElementById('productBreakCheck');
                    const selectedQuantity = document.getElementById('selectedQuantity');
                    const form = document.getElementById('productBreakOrMoveForm');
                    const submitButton = document.getElementById('submitButton');
                    if (productMove.checked) {
                        console.log(productMove.value);
                        productBreak.checked = false;
                        selectedQuantity.disabled = true;
                        form.action = '/storage/productMove/addRow';
                        submitButton.value = 'Termék mozgatás'
                    }
                    productMove.addEventListener('change', function () {
                        console.log('ott');
                        if (productMove.checked) {
                            productBreak.checked = false;
                            selectedQuantity.disabled = true;
                            form.action = '/storage/productMove/addRow';
                            submitButton.value = 'Termék mozgatás'
                        }
                    });
                    productBreak.addEventListener('change', function () {
                        if (productBreak.checked) {
                            productMove.checked = false;
                            selectedQuantity.disabled = false;
                            form.action = '/storage/productBreak/addRow';
                            submitButton.value = 'Termék bontás'
                        }
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
