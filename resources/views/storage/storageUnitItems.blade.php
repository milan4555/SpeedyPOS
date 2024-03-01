@extends('storage.storageUnitTemplate')
@section('storageUnitContent')
    <div class="p-2">
        <h3 class="text-center"><b>{{$selectedStorage->storageId}}-{{$letter}}{{$width}}-{{$height}}</b></h3>
        <hr>
        <table id="productTable" class="table w-100">
            @if(count($products) == 0)
                <tr>
                    <td class="text-center" colspan="8"><h3>Ezen a polcon nincsen megjeleníthető adat!<br>Töltsd fel először áruval, aztán térj vissza!</h3></td>
                </tr>
            @else
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
            @endif
        </table>
    </div>
@endsection
