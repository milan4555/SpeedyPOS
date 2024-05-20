@extends('storage.storageUnits.storageUnitTemplate')
@section('storageUnitContent')
    <div class="p-2">
        <h3 class="text-center pt-2"><b>{{$selectedStorage->storageId}}-{{$letter}}{{$height}}-{{$width}}</b></h3>
        <hr>
        <div class="table-responsive">
            <table id="productTable" class="table border border-2 border-dark w-100">
                @if(count($products) == 0)
                    <tr>
                        <td class="text-center" colspan="8">
                            <h3>
                                Ezen a polcon nincsen megjeleníthető adat!<br>
                                Töltsd fel először áruval, aztán térj vissza!
                            </h3>
                        </td>
                    </tr>
                @else
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
                        <tr>
                            <th id="productId">{{$product->productId}}-{{$product->index}}</th>
                            <td id="productName">{{$product->productName}}</td>
                            <td id="productShortName">{{$product->productShortName}}</td>
                            <td id="categoryId">{{$product->categoryName}}</td>
                            <td id="companyId" class="{{$product->companyName == null ? 'text-danger' : ''}}">{{$product->companyName == null ? 'Nincs beállítva!' : $product->companyName}}</td>
                            <td id="bPrice">{{$product->bPrice}} Ft.</td>
                            <td id="nPrice">{{$product->nPrice}} Ft.</td>
                            <td id="stock">{{$product->howMany}} db</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
        <hr>
    </div>
@endsection
