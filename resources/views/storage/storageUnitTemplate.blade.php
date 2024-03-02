@extends('layouts.menu')
@section('content')
    @php($abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ")
    <div class="bg-white m-3">
        <div class="d-flex justify-content-between p-2 px-5">
            <div>
                <select id="storageUnitSelector" class="form-control border border-3 border-dark">
                    <option value="0">Válassz ki egy raktárhelységet!...</option>
                    @foreach($storageUnits as $storageUnit)
                        <option value="{{$storageUnit->storageId}}" {{$selectedStorageId == $storageUnit->storageId ? 'selected' : ''}}>
                            {{$storageUnit->storageName}}
                        </option>
                    @endforeach
                </select>
                <script>
                    const storageSelector = document.getElementById('storageUnitSelector');
                    storageSelector.addEventListener('change', function() {
                        window.location.href = "/storage/storageUnits/" + storageSelector.value;
                    });
                </script>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newStorageModal">
                    Új raktár létrehozás
                </button>
                <a class="btn btn-warning" href="/storage/print/{{isset($letter) ? (isset($width) ? 'specific/'.$selectedStorageId.'/'.$letter.'/'.$width.'/'.$height : 'row/'.$selectedStorageId.'/'.$letter) : 'rows/'.$selectedStorageId}}">{{isset($letter) ? (isset($width) ? 'Polccímke' : 'Polccímkék') : 'Sorcímkék'}} nyomtatása</a>
                <a class="btn btn-danger" href="{{isset($width) ? '/storage/storageUnit/'.$selectedStorageId.'/'.$letter : '/storage/storageUnits/'.$selectedStorageId.'/'}}">Vissza</a>
                @include('storage.modals._newStorageUnit')
            </div>
        </div>
        <hr>
            @yield('storageUnitContent')
        <hr>
    </div>
@endsection
