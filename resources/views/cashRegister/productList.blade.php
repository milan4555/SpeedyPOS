@extends('cashRegister/cashRegisterTemplate')
@section('mainSpace')
    <table class="table">
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Termék neve</th>
            <th scope="col">Rövid név</th>
            <th scope="col">Kategória</th>
            <th scope="col">Termék ára</th>
            <th scope="col">Elérhető mennyiség</th>
            <th scope="col"></th>
        </tr>
        @foreach($products as $product)
            <tr>
                <th>{{$product->productId}}</th>
                <td>{{$product->productName}}</td>
                <td>{{$product->productShortName}}</td>
                <td>{{$product->categoryName}}</td>
                <td>{{$product->nPrice}} Ft</td>
                <td>{{$product->stock}} db</td>
                <td><a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseProductCodes{{$product->productId}}" role="button" aria-expanded="false" aria-controls="collapseExample">Kódok</a></td>
            </tr>
            <tr class="collapse" id="collapseProductCodes{{$product->productId}}">
                <td colspan="7">
                    @foreach(\App\Http\Controllers\ProductCodesController::getAllCodesByProductId($product->productId) as $code)
                        {{$code->productCode}} <-->
                    @endforeach
                </td>
            </tr>
        @endforeach
    </table>
@endsection

@section('buttons')
    <div class="container-fluid">
        <form class=" mt-2">
            @csrf
            <div class="row">
            <div class="col-md-6">
                Keresés szöveg alapján:
                <select class="form-control" name="columnSearch">
                    <option value="">Válassz szűrési lehetőséget!</option>
                    <option value="productId">Azonosító</option>
                    <option value="productName">Termék név</option>
                    <option value="productShortName">Rövidnév</option>
                    <option value="categoryName">Kategória</option>
                </select>
                <input class="form-control mt-2" type="text" placeholder="Pl.: Festék" name="search">
            </div>
            <div class="col-md-6">
                Rendezés oszlop szerint:
                <select class="form-control" name="columnOrderBy">
                    <option value="">Válassz rendezési lehetőséget!</option>
                    <option value="productId">Azonosító</option>
                    <option value="productName">Termék név</option>
                    <option value="productShortName">Rövidnév</option>
                    <option value="categoryName">Kategória</option>
                </select>
            </div>
            </div>
            <div class="d-flex justify-content-center">
                <input class="form-control btn btn-primary mt-2 w-50" type="submit" value="Szűrés">
            </div>
        </form>
    </div>
@endsection
