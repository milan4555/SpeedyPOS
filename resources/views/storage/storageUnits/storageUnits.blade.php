@extends('storage.storageUnits.storageUnitTemplate')
@section('storageUnitContent')
    @php($abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ")
        @if(isset($selectedStorageId) and $selectedStorageId != 0)
            <div class="d-flex flex-wrap justify-content-around my-4 py-3">
                @for($i = 0;$i < $selectedStorage->numberOfRows;$i++)
                    <div onclick="window.location.href = '/storage/storageUnit/{{$selectedStorageId}}/{{$abc[$i]}}'" class="storage-unit-select">
                        <div class="d-flex bg-white align-items-center p-3 storage-unit-background"
                             style="height: 300px; border-bottom: solid 2px black"></div>
                        <div class="bg-white border border-2 border-dark rounded-bottom-4 p-2">
                            {{$selectedStorageId}}-{{$abc[$i]}}1-1<br>
                            {{$selectedStorageId}}-{{$abc[$i]}}{{$selectedStorage->heightNumber}}-{{$selectedStorage->widthNumber}}
                        </div>
                    </div>
                @endfor
            </div>
        @endif
@endsection
