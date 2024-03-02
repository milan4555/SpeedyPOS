@extends('storage.storageUnitTemplate')
@section('storageUnitContent')
    @php($abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ")
    @for($i = $selectedStorage->heightNumber;$i > 0;$i--)
        <div class="d-flex justify-content-center">
            @for($j = 0;$j < $selectedStorage->widthNumber;$j++)
                <a href="/storage/storageUnit/{{$selectedStorageId}}/{{$letter}}/{{$j}}/{{$i+1}}" style="text-decoration: none">
                    <div class="border rounded border-2 border-danger p-3 text-center p-auto" style="width: 100px; height: 100px">
                        {{$selectedStorage->storageId}}-{{$letter}}{{$i}}-{{$j+1}}
                    </div>
                </a>
            @endfor
        </div>
    @endfor
@endsection
