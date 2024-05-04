@extends('layouts.menu')
@section('content')
    @php($abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ")
    <div class="bg-white border border-3 rounded-4 border-dark m-3">
        <div class="d-flex justify-content-between flex-wrap p-2 px-5" style="border-bottom: solid 3px black">
            <div>
                <div class="row">
                    <div class="col-md-8">
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
                        <input id="searchStorageUnit" placeholder="Keresés..." class="form-control border border-3 border-dark">
                    </div>
                </div>
                <script>
                    const storageSelector = document.getElementById('storageUnitSelector');
                    const storageSearchBar = document.getElementById('searchStorageUnit');
                    storageSelector.addEventListener('input', function() {
                        window.location.href = "/storage/storageUnits/" + storageSelector.value;
                    });

                    storageSearchBar.addEventListener('keypress', function (e) {
                        if (e.key === 'Enter') {
                            window.location.href = '/storage/searchUnit/' + storageSearchBar.value;
                        }
                    })
                </script>
            </div>
            <div>
                <button type="button" class="btn button-blue" data-bs-toggle="modal" data-bs-target="#newStorageModal">
                    Új raktár létrehozás
                </button>
                <a class="btn button-orange mx-2" href="/storage/print/{{isset($letter) ? (isset($width) ? 'specific/'.$selectedStorageId.'/'.$letter.'/'.$width.'/'.$height : 'row/'.$selectedStorageId.'/'.$letter) : 'rows/'.$selectedStorageId}}">{{isset($letter) ? (isset($width) ? 'Polccímke' : 'Polccímkék') : 'Sorcímkék'}} nyomtatása</a>
                <a class="btn button-red" href="{{isset($letter) ? (isset($width) ? '/storage/storageUnit/'.$selectedStorageId.'/'.$letter : '/storage/storageUnits/'.$selectedStorageId.'/') : '/storage/menu'}}">Vissza</a>
                @include('storage.modals._newStorageUnit')
            </div>
        </div>
        @if($selectedStorageId == 0)
            <div class="p-5 text-center">
                <h1>Válassz egy raktárhelységet a fenti listából, vagy keress rá egy specifikus raktárhelykódra az adatok megjelnítéséhez!</h1>
            </div>
        @else
            @yield('storageUnitContent')
        @endif
    </div>
@endsection
