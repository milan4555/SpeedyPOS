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
    <div class="bg-light m-3 p-2 border border-3 border-dark">
        <div class="row px-2">
            <div class="col-md-8 p-2 border border-2 border-dark">
                    <form id="productForm" method="post" action="/storage/addProduct">
                        @csrf
                        <input type="hidden" name="productId" id="productId">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label" for="productName">Termék név:</label>
                                <input class="form-control" type="text" id="productName" name="productName" autocomplete="off" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="productShortName">Rövid név:</label>
                                <input class="form-control" type="text" id="productShortName" name="productShortName" autocomplete="off" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="nPrice">Nettó ár:</label>
                                <input class="form-control" type="number" id="nPrice" name="nPrice" autocomplete="off" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="bPrice">Bruttó ár:</label>
                                <input class="form-control" type="number" id="bPrice" name="bPrice" autocomplete="off" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="categoryId">Kategória:</label>
                                <select class="form-control" name="categoryId" id="categoryId" autocomplete="off" required>
                                    <option value="">...</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{$category->categoryId}}">{{$category->categoryName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="companyId">Beszállító:</label>
                                <select class="form-control" name="companyId" id="companyId" autocomplete="off" required>
                                    <option value="">...</option>
                                    @foreach(\App\Models\Company::all()->where('isSupplier', '=', true) as $supplier)
                                        <option value="{{$supplier->companyId}}">{{$supplier->companyName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex pt-2 justify-content-center">
                                <input class="btn btn-primary mx-2" type="submit" value="Mentés">
                                <button type="button" class="btn btn-warning" onclick="emptyForm()">Új felvétele</button>
                            </div>
                        </div>
                    </form>
            </div>
            <div id="productCodesPlace" class="col-md-4 border border-2 border-dark">
            </div>
        </div>
        <hr>
        <div class="my-custom-scrollbar table-wrapper-scroll-y">
        <table id="productTable" class="table w-100">
            <tr>
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
                    <th id="productId" data-input="{{$product->productId}}">{{$product->categoryId.str_repeat(0,7-strlen($product->productId)).$product->productId}}</th>
                    <td id="productName" data-input="{{$product->productName}}">{{$product->productName}}</td>
                    <td id="productShortName" data-input="{{$product->productShortName}}">{{$product->productShortName}}</td>
                    <td id="categoryId" data-input="{{$product->categoryId}}">{{$product->categoryName}}</td>
                    <td id="companyId" data-input="{{$product->companyId}}" class="{{$product->companyName == null ? 'text-danger' : ''}}">{{$product->companyName == null ? 'Nincs beállítva!' : $product->companyName}}</td>
                    <td id="bPrice" data-input="{{$product->bPrice}}">{{$product->bPrice}}</td>
                    <td id="nPrice" data-input="{{$product->nPrice}}">{{$product->nPrice}}</td>
                    <td id="stock" data-input="{{$product->stock}}">{{$product->stock}} db</td>
                </tr>
            @endforeach
        </table>
        </div>
    </div>
    <script>
        const productTable = document.getElementById('productTable');
        const ids = [];
        function getItemsFromRow(rowId) {
            const row = document.getElementById(rowId);
            console.log(row.dataset.productcodes)
            for (let i = 0; i < row.dataset.productcodes.length; i++) {
                console.log(row.dataset.productcodes[i])
            }
            for (let i = 0; i < productTable.rows[1].cells.length; i++) {
                document.getElementById(row.cells[i].id).value = row.cells[i].dataset.input;
                document.getElementById('productForm').action = '/storage/updateProduct';
                document.getElementById('nPrice').disabled = true;
                document.getElementById('bPrice').disabled = true;
            }
            document.getElementById('productCodesPlace').innerHTML = row.dataset.productcodes;
            return rowId;
        }
        function emptyForm() {
            for (let i = 0; i < productTable.rows[1].cells.length; i++) {
                document.getElementById(productTable.rows[1].cells[i].id).value = null;
                document.getElementById('productForm').action = '/storage/addProduct';
            }
        }
    </script>
@endsection
