@extends('layouts.menu')
@section('content')
    <style>
        .my-custom-scrollbar {
            height: 290px;
            overflow: auto;
        }
        .table-wrapper-scroll-y {
            display: block;
        }
    </style>
    <div class="bg-light m-3 p-3 border border-3 rounded-4 border-dark">
        <div class="row px-2">
            <div class="col-md-8 p-2">
                    <form id="productForm" method="post" action="/storage/addProduct">
                        @csrf
                        <input type="hidden" name="productId" id="productId">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label" for="productName">Termék név:</label>
                                <input class="form-control border-dark" type="text" id="productName" name="productName" autocomplete="off" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="productShortName">Rövid név:</label>
                                <input class="form-control border-dark" type="text" id="productShortName" name="productShortName" autocomplete="off" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="nPrice">Nettó ár:</label>
                                <input class="form-control border-dark" type="number" id="nPrice" name="nPrice" autocomplete="off" required>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label class="form-label" for="categoryId">Kategória:</label>
                                        <select class="form-select border-dark" name="categoryId" id="categoryId" autocomplete="off" required>
                                            <option value="">...</option>
                                            @foreach(\App\Models\Category::all() as $category)
                                                <option value="{{$category->categoryId}}">{{$category->categoryName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <br>
                                        <button id="newCategoryButton" class="btn button-blue mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#newCategoryNameInput" aria-expanded="false" aria-controls="newCategoryNameInput">
                                            +
                                        </button>
                                    </div>
                                    <div class="col-md-12 collapse" id="newCategoryNameInput">
                                        <label class="form-label" for="newCategoryName">Új kategória neve:</label>
                                        <input id="newCategoryName" class="form-control border-dark" type="text" name="newCategoryName">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="companyId">Beszállító:</label>
                                <select class="form-select border-dark" name="companyId" id="companyId" autocomplete="off" required>
                                    <option value="">...</option>
                                    @foreach(\App\Models\Company::all()->where('isSupplier', '=', true) as $supplier)
                                        <option value="{{$supplier->companyId}}">{{$supplier->companyName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="bPrice">Bruttó ár:</label>
                                <input class="form-control border-dark" type="number" id="bPrice" name="bPrice" autocomplete="off" required>
                            </div>
                            <div class="d-flex pt-2 justify-content-center">
                                <button type="button" class="btn button-red" onclick="window.location.href = '/storage/menu'" style="margin: 0">Vissza a menübe</button>
                                <input class="btn button-blue mx-2" type="submit" value="Mentés" style="margin: 0">
                                <button type="button" class="btn button-orange" onclick="emptyForm()" style="margin: 0">Új felvétele</button>
                            </div>
                        </div>
                    </form>
            </div>
            <div id="productCodesPlace" class="col-md-4 border-start border-5 border-dark">
                <h2>Válassz ki egy terméket a tabálázatból, vagy hozz létre egy újat!</h2>
            </div>
        </div>
        <hr>
{{--        <div class="row">--}}
{{--            <div class="col-md-5">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <input class="form-control mb-2 border-dark" type="text" id="productSearchInput" placeholder="Keresés...">--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <select class="form-select border-dark" id="productSearchFilter">--}}
{{--                            <option selected value="0">Azonosító</option>--}}
{{--                            <option value="1">Termék neve</option>--}}
{{--                            <option value="2">Rövid név</option>--}}
{{--                            <option value="3">Kategória</option>--}}
{{--                            <option value="4">Beszállító</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="my-custom-scrollbar table-wrapper-scroll-y">
        <table id="productTable" class="table table-hover w-100 border border-2 border-dark">
            <tr class="table-dark">
                <th scope="col">Azonosító</th>
                <th scope="col">Termék neve</th>
                <th scope="col">Rövid név</th>
                <th scope="col">Kategória</th>
                <th scope="col">Beszállító</th>
                <th scope="col">Bruttó ár</th>
                <th scope="col">Nettó ár</th>
                <th scope="col">Elérhető mennyiség</th>
            </tr>
            @foreach($products as $product)
                <tr id="{{$product->productId}}" onclick="getItemsFromRow({{$product->productId}})" data-productCodes="{{\App\Http\Controllers\ProductCodesController::makeTable($product->productId)}}">
                    <th id="productId" data-input="{{$product->productId}}">{{$product->productId}}</th>
                    <td id="productName" data-input="{{$product->productName}}">{{$product->productName}}</td>
                    <td id="productShortName" data-input="{{$product->productShortName}}">{{$product->productShortName}}</td>
                    <td id="categoryId" data-input="{{$product->categoryId}}">{{$product->categoryName}}</td>
                    <td id="companyId" data-input="{{$product->companyId}}" class="{{$product->companyName == null ? 'text-danger' : ''}}">{{$product->companyName == null ? 'Nincs beállítva!' : $product->companyName}}</td>
                    <td id="bPrice" data-input="{{$product->bPrice}}">{{$product->bPrice}} Ft.</td>
                    <td id="nPrice" data-input="{{$product->nPrice}}">{{$product->nPrice}} Ft.</td>
                    <td id="stock" data-input="{{$product->stock}}">{{$product->stock}} db</td>
                </tr>
            @endforeach
        </table>
        </div>
    </div>
    <script>
        const productTable = document.getElementById('productTable');
        const ids = [];
        const productCodeDiv = document.getElementById('productCodesPlace');
        function getItemsFromRow(rowId) {
            const row = document.getElementById(rowId);
            for (let i = 0; i < productTable.rows[1].cells.length; i++) {
                document.getElementById(row.cells[i].id).value = row.cells[i].dataset.input;
            }
            document.getElementById('productForm').action = '/storage/updateProduct';
            document.getElementById('nPrice').disabled = true;
            document.getElementById('bPrice').disabled = true;
            console.log(row.dataset.productcodes)
            productCodeDiv.innerHTML = row.dataset.productcodes;
            return rowId;
        }
        const isTrue = <?php echo json_encode(session()->has('redirectProductId')) ?>;
        if (isTrue) {
            getItemsFromRow(<?php echo json_encode(session('redirectProductId')) ?>);
        }
        function emptyForm() {
            for (let i = 0; i < productTable.rows[1].cells.length; i++) {
                document.getElementById(productTable.rows[1].cells[i].id).value = null;
                document.getElementById('productForm').action = '/storage/addProduct';
            }
            const nPrice = document.getElementById('nPrice');
            const bPrice = document.getElementById('bPrice');
            const supplierSelect = document.getElementById('companyId');
            supplierSelect.value = '';
            nPrice.disabled = false;
            bPrice.disabled = false;
            productCodeDiv.innerHTML = '<h2>Válassz ki egy terméket a tábálázatból, vagy hozz létre egy újjat!</h2>';
        }
        const newCategoryInput = document.getElementById('newCategoryName');
        const categorySelect = document.getElementById('categoryId');
        const newCategoryButton = document.getElementById('newCategoryButton');
        const newCategoryDiv = document.getElementById('newCategoryNameInput');
        newCategoryInput.addEventListener('input', function () {
            categorySelect.disabled = newCategoryInput.value !== '';
        });
        categorySelect.addEventListener('input', function () {
            newCategoryInput.disabled = categorySelect.value !== '';
            newCategoryButton.disabled = categorySelect.value !== '';
        });
        function addProductCode(productId) {
            const productCodeInput = document.getElementById('newProductCode' + productId);
            window.location.href = '/storage/newProductCode/' + productId + '/' + productCodeInput.value;
        }
    </script>
@endsection
