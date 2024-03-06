@extends('layouts.menu')
@section('content')
    <style>
        td {
            font-size: 16px;
        }
    </style>
    <div class="m-3 p-3 border border-dark rounded-3 bg-white">
        <table class="table table-bordered border-dark">
            <thead>
                <tr>
                    <td colspan="2">
                        <input type="number" id="productIdentifier" class="form-control border-dark" placeholder="Termék kód helye" name="productIdentifier">
                    </td>
                    <td colspan="2">
                        <select id="supplierId" class="form-control border-dark" name="supplierId">
                            <option value="0">Válassz beszállítót!</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->companyId}}" {{$selectedSupplierId == $supplier->companyId ? 'selected' : ''}}>{{$supplier->companyName}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-center" colspan="3"><a class="btn btn-primary">Befejezés</a></td>
                </tr>
                <tr>
                    <th>Termék azonosító</th>
                    <th>Termék megnevezése</th>
                    <th>Kategória</th>
                    <th class="col-sm-1">Darabszám</th>
                    <th class="col-sm-1">Beszerzési ár</th>
                    <th class="col-sm-1">Eladási ár</th>
                    <th class="col-sm-1"></th>
                </tr>
            </thead>
            <tbody>
                @if(isset($products))
                    @foreach($products as $product)
                        <tr id="{{$product->productId}}">
                            <td class="col-md-2 align-middle">{{$product->productId}}</td>
                            <td class="align-middle">{{$product->productName}}</td>
                            <td class="align-middle">{{$product->categoryName}}</td>
                            <td><input type="number" id="quantity.{{$product->productId}}" class=" form-control border-dark" name="quantity" value="{{$product->howMany}}" required></td>
                            <td><input type="number" id="bPrice.{{$product->productId}}" class="form-control border-dark" name="bPrice" value="{{$product->newBPrice}}" required></td>
                            <td id="nPrice" class="align-middle">{{round($product->newBPrice * 1.8, -1)}} Ft</td>
                            <td class="text-center"><a href="/storage/productIn/removeRow/{{$product->productId}}" class="btn btn-danger">Törlés</a></td>
                            <script>
                                const bPrice{{$product->productId}} = document.getElementById('bPrice.{{$product->productId}}');
                                const quantity{{$product->productId}} = document.getElementById('quantity.{{$product->productId}}');
                                bPrice{{$product->productId}}.addEventListener('change', function () {
                                    window.location.href = '/storage/productIn/changeBPrice/' + {{$product->productId}} + '/' +  bPrice{{$product->productId}}.value;
                                });
                                quantity{{$product->productId}}.addEventListener('change', function () {
                                    window.location.href = '/storage/productIn/changeQuantity/' + {{$product->productId}} + '/' +  quantity{{$product->productId}}.value;
                                });
                            </script>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6"><h3 class="text-center pt-2">Nincsen még felvéve semmilyen termék!</h3></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <script>
        const productIdentifier = document.getElementById('productIdentifier')
        const supplierId = document.getElementById('supplierId');
        productIdentifier.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                window.location.href = '/storage/productIn/addRow/' + productIdentifier.value;
            }
        });
        supplierId.addEventListener('change', function () {
            window.location.href = '/storage/productIn/addSupplier/' + supplierId.value;
        });
    </script>
@endsection
