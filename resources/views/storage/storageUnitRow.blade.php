@extends('storage.storageUnitTemplate')
@section('storageUnitContent')
    @php($abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ")
    @for($i = 0;$i < $selectedStorage->heightNumber;$i++)
        <div class="d-flex justify-content-center">
            @for($j = 0;$j < $selectedStorage->widthNumber;$j++)
                <a href="/storage/storageUnit/{{$selectedStorageId}}/{{$letter}}/{{$j+1}}/{{$i+1}}" style="text-decoration: none">
                    <div class="border rounded border-2 border-danger p-3 text-center p-auto" style="width: 100px; height: 100px">
                        {{$selectedStorage->storageId}}-{{$letter}}{{$i+1}}-{{$j+1}}
                    </div>
                </a>
            @endfor
        </div>
    @endfor
@endsection
