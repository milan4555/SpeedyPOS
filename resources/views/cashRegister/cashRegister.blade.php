@extends('layouts/menu')
@section('content')
    <div class="container-fluid">
        <div class="row bg-dark m-3 p-4">
            <div class="col-md-8 bg-white border border-dark border-2 rounded overflow-auto"  style="height: 400px">
                <table class="table w-100">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col">Termék azonosító</th>
                        <th scope="col">Termék neve</th>
                        <th scope="col">Ár</th>
                        <th scope="col">Darabszám</th>
                        <th scope="col">Funkciók</th>
                    </tr>
                    </thead>
                    <tbody>
                @if($lastProduct != 'Üres a kosár!' and $lastProduct != 'Nem megfelelő kódot adtál meg!')
                        <tr class="table-active">
                            <th>{{$lastProduct->categoryId.str_repeat(0,7-strlen($lastProduct->productId)).$lastProduct->productId}}</th>
                            <td>{{$lastProduct->productName}}</td>
                            <td>{{$lastProduct->bPrice}} Ft</td>
                            <td>{{\App\Http\Controllers\ProductController::getHowManyInCart($lastProduct->productId, $howMany)}} db</td>
                            <td></td>
                        </tr>
                @elseif($lastProduct == 'Üres a kosár!')
                    <h5>{{$lastProduct}}</h5>
                @else
                    <h5 class="text-center text-danger pt-2 pb-2">Hiba: <b>{{$lastProduct}}</b></h5>
                @endif
                @if(isset($products))
                    @foreach($products as $product)
                        <tr>
                            <th scope="row">{{$product->categoryId.str_repeat(0,7-strlen($product->productId)).$product->productId}}</th>
                            <td>{{$product->productName}}</td>
                            <td>{{$product->bPrice}} Ft</td>
                            <td>{{\App\Http\Controllers\ProductController::getHowManyInCart($product->productId, $howMany)}} db</td>
                            <td></td>
                        </tr>
                    @endforeach
                @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-4 bg-white border border-dark border-2 rounded">
                Funkciók helye
            </div>
            <div class="col-md-6 bg-white border border-dark border-2 rounded">
                <form method="get" action="/cashRegister">
                    <input type="number" name="lastProductId" class="form-control w-100 h-100" autocomplete="off" autofocus>
                    @foreach($productIds as $productId)
                        <input type="hidden" name="productIds[]" value="{{$productId}}">
                    @endforeach
                    @foreach($howMany as $data)
                        <input type="hidden" name="howMany[]" value="{{$data}}">
                    @endforeach
                </form>
            </div>
            <div class="col-md-2 bg-white border border-dark border-2 rounded">
                <h5 class="mt-1">Teljes összeg: {{$sumPrice}} Ft.</h5>
            </div>
        </div>
    </div>
@endsection()
