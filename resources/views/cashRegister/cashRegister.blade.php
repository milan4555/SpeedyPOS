@extends('cashRegister/cashRegisterTemplate')
@section('mainSpace')
    @if($lastProduct == 'Üres a kosár!')
        @if($companyCurrent != null)
            <tr>
                <td colspan="6"><b>Jelenlegi cég:</b> {{$companyCurrent->companyName}} {{$companyCurrent->taxNumber}} ({{$companyCurrent->city}}, {{$companyCurrent->street}} {{$companyCurrent->streetNumber}}.)</td>
            </tr>
        @endif
        <h3 class="text-center pt-2 pb-2">{{$lastProduct}}</h3>
    @else
        <table class="table w-100">
            @if($companyCurrent != null)
                <tr>
                    <td colspan="6"><b>Jelenlegi cég:</b> {{$companyCurrent->companyName}} {{$companyCurrent->taxNumber}} ({{$companyCurrent->city}}, {{$companyCurrent->street}} {{$companyCurrent->streetNumber}}.)</td>
                </tr>
            @endif
            <thead class="table-dark">
            <tr>
                <th scope="col">Termék azonosító</th>
                <th scope="col">Termék neve</th>
                <th scope="col">Kategória</th>
                <th scope="col">Ár</th>
                <th scope="col">Darabszám</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @if($lastProduct == 'Nem megfelelő kódot adtál meg!')
                <tr>
                    <td colspan="5">
                        <h3 class="text-center text-danger pt-2 pb-2">Hiba: <b>{{$lastProduct}}</b></h3>
                    </td>
                </tr>
            @else
                @if($lastProduct->howMany != -1)
                    <tr class="table-active">
                        <th>{{$lastProduct->categoryId.str_repeat(0,7-strlen($lastProduct->productId)).$lastProduct->productId}}</th>
                        <td>{{$lastProduct->productName}}</td>
                        <td><i>{{$lastProduct->categoryName}}</i></td>
                        <td>{{$lastProduct->bPrice}} Ft</td>
                        <td>{{$lastProduct->howMany}} db</td>
                        <td class="d-flex justify-content-end">
                            <form class="collapse in" id="collapseQuantity{{$lastProduct->productId}}" method="post" action="/cashRegister/changeQuantity">
                                @csrf
                                <input type="number" class="form-control" name="quantity">
                                <input type="hidden" name="productId" value="{{$lastProduct->productId}}">
                            </form>
                            <a data-bs-toggle="collapse" href="#collapseQuantity{{$lastProduct->productId}}" role="button" aria-expanded="false" aria-controls="collapseExample"><img src="{{asset('iconsAndLogos/editIcon.png')}}"></a>
                            <a class="btn-close" href="/cashRegister/deleteItem/1/{{$lastProduct->productId}}" style="text-decoration: none;"></a>
                        </td>
                    </tr>
                @endif
            @endif
            @if(isset($products))
                @foreach($products as $product)
                    <tr>
                        <th scope="row">{{$product->categoryId.str_repeat(0,7-strlen($product->productId)).$product->productId}}</th>
                        <td>{{$product->productName}}</td>
                        <td><i>{{$product->categoryName}}</i></td>
                        <td>{{$product->bPrice}} Ft</td>
                        <td>{{$product->howMany}} db</td>
                        <td class="d-flex justify-content-end">
                            <form class="collapse in" id="collapseQuantity{{$product->productId}}" method="post" action="/cashRegister/changeQuantity">
                                @csrf
                                <input type="text" class="form-control" name="quantity">
                                <input type="hidden" name="productId" value="{{$product->productId}}">
                            </form>
                            <a data-bs-toggle="collapse" href="#collapseQuantity{{$product->productId}}" role="button" aria-expanded="false" aria-controls="collapseExample"><img src="{{asset('iconsAndLogos/editIcon.png')}}"></a>
                            <a class="btn-close" href="/cashRegister/deleteItem/1/{{$product->productId}}" style="text-decoration: none;"></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            @endif
            </tbody>
        </table>
@endsection

@section('buttons')
    <button type="button" class="btn btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#emptyCashRegisterModal">Megszakítás</button>
    @include('cashRegister\modals\_emptyCashRegisterModal')
    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-primary w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChange" aria-expanded="false" aria-controls="collapseChange">
                Készpénzes fizetés
            </button>
        </div>
        <div class="col-md-6">
            <a type="button" class="btn btn-primary w-100 mt-2" href="/cashRegister/makeReceipt/B/0">Bankártyás fizetés</a>
        </div>
        <div class="collapse row mt-2" id="collapseChange">
            <div class="col-md-7">
                <input type="number" pattern="[0-5][0]{1}" class="form-control" id="changeAmount" min="{{$sumPrice}}" placeholder="Visszajáró" required>
            </div>
            <div class="col-md-5">
                <a type="button" id="success" class="btn btn-danger w-100">Véglegesít</a>
            </div>
        </div>
    </div>
    <script>
        const input = document.getElementById('changeAmount');
        const successButton = document.getElementById('success');
        input.addEventListener('change', (event) => {
            if (input.validity.valid && (input.value % 5 === 0 || input.value % 10 === 0)) {
                successButton.classList.add('btn-success')
                successButton.classList.remove('btn-danger')
                successButton.href = '/cashRegister/makeReceipt/K/' + input.value;
            } else {
                successButton.classList.add('btn-danger')
                successButton.classList.remove('btn-success')
                successButton.removeAttribute("href");
            }
        });
    </script>
    <form class="row" action="/cashRegister/changeCompany" method="get">
        <div class="col-md-9">
            <select name="companyId" class="form-control mt-2">
                <option value="0">Cég: Nincs kiválasztva!</option>
                @foreach(\App\Models\Company::all()->sortBy('companyName') as $company)
                    <option value="{{$company->companyId}}" {{($companyCurrent != null and $company->companyId == $companyCurrent->companyId) ? 'selected' : ''}}>{{$company->companyName}} ({{$company->city}}, {{$company->street}} {{$company->streetNumber}}.)</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-warning w-100 mt-2" type="submit">Hozzáadás</button>
        </div>
    </form>
@endsection

@section('other')
    <div class="col-md-6 bg-white border border-dark border-2 rounded">
        <form action="{{url('/cashRegister')}}" method="post">
            @csrf
            <input type="text" name="lastProductId" class="form-control w-100 h-100" autocomplete="off" autofocus>
        </form>
    </div>
    <div class="col-md-2 bg-white border border-dark border-2 rounded">
        <h5 class="mt-1">Teljes összeg: {{$sumPrice}} Ft.</h5>
    </div>
@endsection
