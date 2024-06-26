@extends('cashRegister/cashRegisterTemplate')
@section('mainSpace')
    <table class="table">
        <thead class="table-dark">
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Termék neve</th>
                <th scope="col">Rövid név</th>
                <th scope="col">Kategória</th>
                <th scope="col">Termék ára</th>
                <th scope="col">Elérhető mennyiség</th>
                <th scope="col"></th>
            </tr>
        </thead>
        @foreach($products as $product)
            <tr>
                <th>{{$product->productId}}</th>
                <td>{{$product->productName}}</td>
                <td>{{$product->productShortName}}</td>
                <td>{{$product->categoryName}}</td>
                <td>{{$product->bPrice}} Ft</td>
                <td>{{$product->stock}} db</td>
                <td><button class="btn button-blue btn-sm"
                       data-bs-toggle="collapse"
                       href="#collapseProductCodes{{$product->productId}}"
                       role="button" aria-expanded="false"
                       aria-controls="collapseExample" {{count(\App\Http\Controllers\ProductCodesController::getAllCodesByProductId($product->productId)) > 0 ? '' : 'disabled'}}>Kódok</button>
                </td>
            </tr>
            <tr class="collapse" id="collapseProductCodes{{$product->productId}}">
                <td colspan="7">
                    @foreach(\App\Http\Controllers\ProductCodesController::getAllCodesByProductId($product->productId) as $code)
                        <span class="badge bg-primary" style="font-size: 14px">{{$code->productCode}}</span>
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
            <div class="col-md-12">
                Keresés szöveg alapján:
                <select class="form-control border-dark" name="columnSearch">
                    <option value="">Válassz szűrési lehetőséget!</option>
                    @foreach($selectOptions as $selectOption)
                        <option value="{{$selectOption[0]}}" {{(isset($columnSearch) and $columnSearch == $selectOption[0]) ? 'selected' : ''}}>{{$selectOption[1]}}</option>
                    @endforeach
                </select>
                <input class="form-control mt-2 border-dark" type="text" placeholder="Pl.: Festék" name="search" value="{{isset($search) ? $search : ''}}">
            </div>
            <div class="col-md-12 mt-2">
                Rendezés oszlop szerint:
                <select class="form-control border-dark" name="columnOrderBy">
                    <option value="">Válassz rendezési lehetőséget!</option>
                    @foreach($selectOptions as $selectOption)
                        <option value="{{$selectOption[0]}}" {{(isset($columnOrderBy) and $columnOrderBy == $selectOption[0]) ? 'selected' : ''}}>{{$selectOption[1]}}</option>
                    @endforeach
                </select>
            </div>
            </div>
            <div class="d-flex justify-content-center mt-2">
                <input class="form-control btn button-blue w-25 mx-1" type="submit" value="Szűrés">
                <a href="/cashRegister/productList" class="btn button-red w-25 mx-1">Törlés</a>
            </div>
        </form>
    </div>
@endsection
