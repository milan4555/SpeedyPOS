@extends('layouts.menu')
@section('content')
    @include('storage.modals._inventoryFullReset')
    @include('storage.modals._inventoryFinish')
    <div class="bg-white m-3">
        <div class="p-3">
            <div class="row">
                <div class="col-md-4">
                    <select id="storageUnitSelector" class="form-control border border-3 border-dark">
                        <option value="0">Válassz ki egy raktárhelységet!...</option>
                        @foreach($storageUnits as $storageUnit)
                            <option value="{{$storageUnit->storageId}}" {{$selectedStorageId == $storageUnit->storageId ? 'selected' : ''}}>
                                {{$storageUnit->storageName}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input id="inventoryInput" class="form-control border border-3 border-dark" placeholder="Cikkszám helye"  {{$selectedStorageId == 0 ? 'disabled' : ''}}>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inventoryFinish" {{$selectedStorageId == 0 ? 'disabled' : ''}}>
                            Befejezés
                        </button>
                        <button type="button" class="btn btn-warning mx-2" data-bs-toggle="modal" data-bs-target="#inventoryFullReset" {{$selectedStorageId == 0 ? 'disabled' : ''}}>
                            Újrakezdés
                        </button>
                        <a href="/storage/menu" class="btn btn-danger">Vissza</a>
                    </div>
                </div>
            </div>
            <hr>
            @if(isset($products))
                <table class="table">
                    <thead class="table-dark">
                    <tr>
                        <th>Cikkszám</th>
                        <th>Termék neve</th>
                        <th>Rövidnév</th>
                        <th>Kategória</th>
                        <th>Darabszám</th>
                        <th>Elhelyezkedés</th>
                        <th>Művelet</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border {{\App\Http\Controllers\InventoryController::getBgColor($lastProduct->isFound, $lastProduct->changedPlace, $lastProduct->quantityDiff)}}">
                        <th>{{$lastProduct->productId}}-{{$lastProduct->index}}</th>
                        <td>{{$lastProduct->productName}}</td>
                        <td>{{$lastProduct->productShortName}}</td>
                        <td>{{\App\Models\Category::find($lastProduct->categoryId)->categoryName}}</td>
                        <td>{{$lastProduct->howMany}} db {{$lastProduct->quantityDiff != 0 ? '('.$lastProduct->quantityDiff.'db)' : ''}}</td>
                        <td>{{$lastProduct->storagePlace}}</td>
                        <td id="{{$lastProduct->storagePlaceId}}"><button class="btn btn-primary btn-sm" onclick="quantityDiff({{$lastProduct->storagePlaceId}})">Eltérő mennyiség</button></td>
                    </tr>
                    @foreach($products as $product)
                        <tr class="border {{\App\Http\Controllers\InventoryController::getBgColor($product->isFound, $product->changedPlace, $product->quantityDiff)}}">
                            <th>{{$product->productId}}-{{$product->index}}</th>
                            <td>{{$product->productName}}</td>
                            <td>{{$product->productShortName}}</td>
                            <td>{{\App\Models\Category::find($product->categoryId)->categoryName}}</td>
                            <td>{{$product->howMany}} db {{$product->quantityDiff != 0 ? '('.$product->quantityDiff.'db)' : ''}}</td>
                            <td>{{$product->storagePlace}}</td>
                            <td class="col-sm-2" id="{{$product->storagePlaceId}}"><button class="btn btn-primary btn-sm" onclick="quantityDiff({{$product->storagePlaceId}})">Eltérő mennyiség</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            <script>
                const storageSelector = document.getElementById('storageUnitSelector');
                storageSelector.addEventListener('change', function() {
                    window.location.href = "/storage/inventory/" + storageSelector.value;
                });
                const productIdInput = document.getElementById('inventoryInput');
                productIdInput.addEventListener('keypress', function (event) {
                   if (event.key === 'Enter') {
                       window.location.href = '/storage/inventoryInput/' + {{$selectedStorageId}} + '/' + productIdInput.value;
                   }
                });
                function quantityDiff(rowId) {
                    const tableColumn = document.getElementById(rowId)
                    tableColumn.innerHTML = "<div class='col-auto'>" +
                        "<input id='quantityInput" + rowId + "' class='form-control-sm border-dark' type='number' placeholder='Mennyiség' style='width: auto;'>" +
                        "<button class='btn btn-sm' onclick='updateRow(" + rowId + ")'>✅</button>" +
                        "<button class='btn text-danger btn-sm' onclick='getButtonBack(" + rowId + ")'>X</button>" +
                        "</div>"
                }
                function updateRow(rowId) {
                    const quantity = document.getElementById('quantityInput' + rowId).value;
                    window.location.href = '/storage/inventoryChangeQuantity/' + rowId + '/' + quantity;
                }
                function getButtonBack(rowId) {
                    console.log(rowId);
                    const tableColumn = document.getElementById(rowId)
                    tableColumn.innerHTML = "<button class='btn btn-primary btn-sm' onclick='quantityDiff(" + rowId + ")'>Eltérő mennyiség</button>"
                }
            </script>
        </div>
    </div>
@endsection
