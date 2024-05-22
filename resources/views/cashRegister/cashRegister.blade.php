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
            </tbody>
        </table>
    @endif
    @include('cashRegister/modals/_changeModal')
    <script>
        document.addEventListener('click', function(e) {
            const checkBox = document.getElementById('productCheckbox' + e.target.parentNode.dataset.productid);
                checkBox.checked = !checkBox.checked;
            });
    </script>
@endsection

@section('buttons')
    <div class="row border-top border-2 border-dark p-2">
        <div class="col-md-6">
            <a id="cashRegisterOpenButton" class="btn button-blue w-100" href="/cashRegister/open/{{\Illuminate\Support\Facades\Auth::id()}}">Kassza nyitás</a>
        </div>
        <script>
            const cashRegisterOpenButton = document.getElementById('cashRegisterOpenButton')
            if ({{\App\Http\Controllers\UserTimeLogController::doesHaveOpenCashRegister(\Illuminate\Support\Facades\Auth::id())}} == true) {
                cashRegisterOpenButton.href = '/cashRegister/close/{{\Illuminate\Support\Facades\Auth::id()}}';
                cashRegisterOpenButton.innerText = 'Kassza zárás';
            }
        </script>
        <div class="col-md-6">
            <a id="cashRegisterBreakButton" class="btn button-orange w-100" href="/cashRegister/haveABreak/{{\Illuminate\Support\Facades\Auth::id()}}">Pénztáros szünet</a>
        </div>
        <script>
            const cashRegisterBreakButton = document.getElementById('cashRegisterBreakButton')
            if ({{\App\Http\Controllers\UserTimeLogController::isOnBreak(\Illuminate\Support\Facades\Auth::id())}} == true) {
                cashRegisterBreakButton.href = '/cashRegister/closeBreak/{{\Illuminate\Support\Facades\Auth::id()}}';
                cashRegisterBreakButton.innerText = 'Munka folytatás';
            }
        </script>
        <div class="col-md-12 mt-2">
            <a id="cashRegisterBreakButton" class="btn button-blue w-100" href="/cashRegister/closeDay">Napi zárás</a>
        </div>
    </div>
    <div class="row border border-2 border-dark p-2" {{\App\Http\Controllers\UserTimeLogController::doesHaveOpenCashRegister(\Illuminate\Support\Facades\Auth::id()) ? '' : 'inert'}}>
        <div class="col-md-12 mb-2">
            <select id="companyId" name="companyId" class="form-control border-dark mt-2">
                <option value="0">Cég: Nincs kiválasztva!</option>
                @foreach(\App\Models\Company::all()->sortBy('companyName') as $company)
                    <option value="{{$company->companyId}}" {{($companyCurrent != null and $company->companyId == $companyCurrent->companyId) ? 'selected' : ''}}>{{$company->companyName}} ({{$company->city}}, {{$company->street}} {{$company->streetNumber}}.)</option>
                @endforeach
            </select>
            <script>
                const companySelector = document.getElementById("companyId")
                companySelector.addEventListener("input", function() {
                    window.location.href = "/cashRegister/changeCompany/" + companySelector.value
                })
            </script>
        </div>
        <div class="col-md-6">
            <button id="cashRegisterQuantityChange" class="btn button-blue w-100" type="button">
                Darabszám
            </button>
        </div>
        <div class="col-md-6">
            <button id="cashRegisterPriceChange" class="btn button-blue w-100" type="button">
                Árfelülírás
            </button>
        </div>
        <div class="col-md-6">
            <button id="cashRegisterSale" class="btn button-blue w-100 mt-2" type="button">
                Kedvezmény
            </button>
        </div>
        <div class="col-md-6">
            <button id="cashRegisterSale" class="btn button-red w-100 mt-2" type="button" onclick="window.location.href='/cashRegister/changeCompany/0'">
                Cég törlés
            </button>
        </div>
        <div id="otherInputDiv" class="row mt-2 mx-auto">
            <div class="col-md-8">
                <input id="otherInput" type="number" class="form-control border-dark">
            </div>
            <div class="col-md-4">
                <button id="otherInputButton" data-type="" class="btn button-red w-100" disabled>Módosít</button>
            </div>
        </div>
        <script>
            const quantityChange = document.getElementById('cashRegisterQuantityChange');
            const priceChange = document.getElementById('cashRegisterPriceChange');
            const sale = document.getElementById('cashRegisterSale');
            const otherInput = document.getElementById('otherInput');
            const otherInputDiv = document.getElementById('otherInputDiv');
            const otherInputButton = document.getElementById('otherInputButton');
            otherInput.addEventListener('input', function () {
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
    <div class="row border border-2 border-dark p-2" {{\App\Http\Controllers\UserTimeLogController::doesHaveOpenCashRegister(\Illuminate\Support\Facades\Auth::id()) ? '' : 'inert'}}>
        <div class="col-md-12">
            <button type="button" class="btn button-red w-100" data-bs-toggle="modal" data-bs-target="#emptyCashRegisterModal">
                Megszakítás
            </button>
            @include('cashRegister\modals\_emptyCashRegisterModal')
        </div>
        <div class="col-md-6">
            <button id="collapseChangeButton" class="btn button-blue w-100 mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChange" aria-expanded="false" aria-controls="collapseChange">
                Készpénzes fizetés
            </button>
        </div>
        <div class="col-md-6">
            <a id="collapseBankCardButton" class="btn button-blue w-100 mt-2" href="/cashRegister/makeReceipt/B/0">
                Bankkártyás fizetés
            </a>
        </div>
        <div class="collapse row mt-2 mx-auto w-100" id="collapseChange">
            <div class="col-md-8">
                <input type="number" pattern="[0-5][0]{1}" class="form-control border-dark" id="changeAmount" min="{{$sumPrice}}" placeholder="Visszajáró" required>
            </div>
            <div class="col-md-4">
                <a type="button" id="success" class="btn button-red w-100">Véglegesít</a>
            </div>
        </div>
    </div>
    <script>
        const input = document.getElementById('changeAmount');
        const successButton = document.getElementById('success');
        input.addEventListener('input', () => {
            if (input.validity.valid && (input.value % 5 === 0 || input.value % 10 === 0)) {
                successButton.classList.add('button-green')
                successButton.classList.remove('button-red')
                successButton.href = '/cashRegister/makeReceipt/K/' + input.value;
            } else {
                successButton.classList.add('button-red')
                successButton.classList.remove('button-green')
                successButton.removeAttribute("href");
            }
        });
    </script>
@endsection

@section('other')
    <div class="col-md-6 bg-dark border border-dark border-2 h-100 rounded" style="padding: 0">
        <form action="{{url('/cashRegister')}}" class="" method="post">
            @csrf
            <input type="text" name="lastProductId" class="form-control-lg w-100 h-100" autocomplete="off" autofocus>
        </form>
    </div>
    <div class="col-md-2 bg-white border border-dark border-2 rounded">
        <h5 class="my-auto">Teljes összeg:<br><b>{{$sumPrice}} Ft.</b></h5>
    </div>
@endsection
