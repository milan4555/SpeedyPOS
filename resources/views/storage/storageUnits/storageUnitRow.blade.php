@extends('storage.storageUnits.storageUnitTemplate')
@section('storageUnitContent')
    @php($abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ")
    <div class="py-3">
        @for($i = $selectedStorage->heightNumber;$i > 0;$i--)
            <div class="d-flex flex-wrap justify-content-center">
                @for($j = 0;$j < $selectedStorage->widthNumber;$j++)
                    <div onclick="window.location.href = '/storage/storageUnit/{{$selectedStorageId}}/{{$letter}}/{{$i}}/{{$j+1}}'"
                         class="border rounded border-3 border-dark text-center pt-2 storage-unit-select-item" style="width: 100px; height: 100px">
                        @if(\App\Http\Controllers\StorageUnitController::checkIfStorageItemIsEmpty($selectedStorage->storageId.'-'.$letter.$i.'-'.$j+1))
                            <img width="50%" src="{{asset('/svgFiles/packageIcon'.rand(1,5).'.svg')}}"><br>
                        @else
                            <img width="50%" src="{{asset('/svgFiles/emptyShelf.svg')}}"><br>
                        @endif
                        <b class="pt-2">{{$selectedStorage->storageId}}-{{$letter}}{{$i}}-{{$j+1}}</b>
                    </div>
                @endfor
            </div>
        @endfor
    </div>
@endsection
