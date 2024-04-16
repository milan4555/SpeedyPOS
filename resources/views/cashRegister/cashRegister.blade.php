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
                    <tr class="table-active" data-productid="{{$lastProduct->productId}}">
                        <th>{{$lastProduct->productId}}</th>
                        <td>{{$lastProduct->productName}}</td>
                        <td><i>{{$lastProduct->categoryName}}</i></td>
                        <td>{{$lastProduct->currentPrice}} Ft</td>
                        <td>{{$lastProduct->howMany}} db</td>
                        <td class="d-flex justify-content-end">
                            <input class="form-check-input mx-2 border-dark productCheckBox" type="checkbox" data-productid="{{$lastProduct->productId}}" id="productCheckbox{{$lastProduct->productId}}" style="width: 20px; height: 20px;">
                            <a class="btn-close" href="/cashRegister/deleteItem/1/{{$lastProduct->productId}}" style="text-decoration: none;"></a>
                        </td>
                    </tr>
                @endif
            @endif
            @if(isset($products))
                @foreach($products as $product)
                    <tr data-productid="{{$product->productId}}">
                        <th scope="row">{{$product->productId}}</th>
                        <td>{{$product->productName}}</td>
                        <td><i>{{$product->categoryName}}</i></td>
                        <td>{{$product->currentPrice}} Ft</td>
                        <td>{{$product->howMany}} db</td>
                        <td class="d-flex justify-content-end">
                            <input class="form-check-input mx-2 border-dark productCheckBox" data-productid="{{$product->productId}}" type="checkbox" id="productCheckbox{{$product->productId}}" style="width: 20px; height: 20px;">
                            <a class="btn-close" href="/cashRegister/deleteItem/1/{{$product->productId}}" style="text-decoration: none;"></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            @endif
            </tbody>
        </table>
        @include('cashRegister/modals/_changeModal')
        <script>
            document.addEventListener('click', function(e) {
                const checkBox = document.getElementById('productCheckbox' + e.target.parentNode.dataset.productid);
                checkBox.checked = !checkBox.checked;
            });
        </script>
@endsection

@section('buttons')
    <button type="button" class="btn btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#popUpModal">Megszakítás</button>
    @include('cashRegister\modals\_emptyCashRegisterModal')
    <div class="row">
        <div class="col-md-6">
            <button id="collapseBankCardButton" class="btn btn-primary w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChange" aria-expanded="false" aria-controls="collapseChange">
                Készpénzes fizetés
            </button>
        </div>
        <div class="col-md-6">
            <button id="collapseChangeButton" class="btn btn-primary w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBankCard" aria-expanded="false" aria-controls="collapseBankCard">
                Bankkártyás fizetés
            </button>
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
    <div class="row">
        <div class="col-md-4">
            <button id="cashRegisterQuantityChange" class="btn btn-primary w-100 mt-2" type="button">
                DB módosítás
            </button>
        </div>
        <div class="col-md-4">
            <button id="cashRegisterPriceChange" class="btn btn-primary w-100 mt-2" type="button">
                Árfelülírás
            </button>
        </div>
        <div class="col-md-4">
            <button id="cashRegisterSale" class="btn btn-primary w-100 mt-2" type="button">
                Kedvezmény
            </button>
        </div>
        <div id="otherInputDiv" class="row mt-2">
            <div class="col-md-9">
                <input id="otherInput" type="number" class="form-control border-dark">
            </div>
            <div class="col-md-3">
                <button id="otherInputButton" data-type="" class="btn btn-danger" disabled>Módosít</button>
            </div>
        </div>
        <script>
            const quantityChange = document.getElementById('cashRegisterQuantityChange');
            const priceChange = document.getElementById('cashRegisterPriceChange');
            const sale = document.getElementById('cashRegisterSale');
            const otherInput = document.getElementById('otherInput');
            const otherInputDiv = document.getElementById('otherInputDiv');
            const otherInputButton = document.getElementById('otherInputButton');
            otherInput.addEventListener('change', function () {
                const otherInputValue = otherInput.value;
                otherInputButton.disabled = (otherInputValue == null || otherInputValue == '');
            });
            otherInputButton.addEventListener('click', function () {
                let type = otherInputButton.dataset.type;
                if (type != null) {
                    const productIds = getCheckBoxes();
                    if (productIds.length != 0) {
                        window.location.href = '/cashRegister/' + type + '/' + JSON.stringify(productIds) + '/' + otherInput.value;
                    }
                }
            })
            function getCheckBoxes() {
                const checkBoxes = document.getElementsByClassName('productCheckBox')
                let productIds = [];
                for (let i = 0; i < checkBoxes.length; i++) {
                    if (checkBoxes[i].checked === true) {
                        productIds.push(checkBoxes[i].dataset.productid);
                    }
                }

                return productIds;
            }
            quantityChange.addEventListener('click', function () {
                otherInput.placeholder = 'Darabszám (db)';
                otherInputButton.dataset.type = 'changeQuantity';
            });
            priceChange.addEventListener('click', function () {
                otherInput.placeholder = 'Termék ár (Ft)';
                otherInputButton.dataset.type = 'changePrice';
            });
            sale.addEventListener('click', function () {
                otherInput.placeholder = 'Százalék (%)';
                otherInputButton.dataset.type = 'pricePercent';
            });
        </script>
    </div>
    <div class="row mt-2">
        <div class="col-md-6">
            <a id="cashRegisterOpenButton" class="btn btn-primary w-100" href="/cashRegister/open/{{\Illuminate\Support\Facades\Auth::id()}}">Kassza nyitás</a>
        </div>
        <script>
            const cashRegisterOpenButton = document.getElementById('cashRegisterOpenButton')
            if ({{\App\Http\Controllers\UserTimeLogController::doesHaveOpenCashRegister(\Illuminate\Support\Facades\Auth::id())}} == true) {
                cashRegisterOpenButton.href = '/cashRegister/close/{{\Illuminate\Support\Facades\Auth::id()}}';
                cashRegisterOpenButton.innerText = 'Kassza zárás';
            }
        </script>
        <div class="col-md-6">
            <a id="cashRegisterBreakButton" class="btn btn-warning w-100" href="/cashRegister/haveABreak/{{\Illuminate\Support\Facades\Auth::id()}}">Pénztáros szünet</a>
        </div>
        <script>
            const cashRegisterBreakButton = document.getElementById('cashRegisterBreakButton')
            if ({{\App\Http\Controllers\UserTimeLogController::isOnBreak(\Illuminate\Support\Facades\Auth::id())}} == true) {
                cashRegisterBreakButton.href = '/cashRegister/closeBreak/{{\Illuminate\Support\Facades\Auth::id()}}';
                cashRegisterBreakButton.innerText = 'Munka folytatás';
            }
        </script>
        <div class="col-md-12 mt-2">
            <a id="cashRegisterBreakButton" class="btn btn-primary w-100" href="/cashRegister/closeDay">Napi zárás</a>
        </div>
    </div>
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
