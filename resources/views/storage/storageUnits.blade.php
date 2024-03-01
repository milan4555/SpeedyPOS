@extends('storage.storageUnitTemplate')
@section('storageUnitContent')
    @php($abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ")
        @if(isset($selectedStorageId) and $selectedStorageId != 0)
            <div class="d-flex justify-content-around">
                @for($i = 0;$i < $selectedStorage->numberOfRows;$i++)
                    <a href="/storage/storageUnit/{{$selectedStorageId}}/{{$abc[$i]}}" style="text-decoration: none">
                        <div class="border rounded border-2 border-danger p-3" style="height: 300px"><br><br><br><br><br>{{$selectedStorageId}}-{{$abc[$i]}}1-1<br>
                                                    {{$selectedStorageId}}-{{$abc[$i]}}{{$selectedStorage->widthNumber}}-{{$selectedStorage->heightNumber}}
                        </div>
                    </a>
                @endfor
            </div>
        @endif
@endsection
