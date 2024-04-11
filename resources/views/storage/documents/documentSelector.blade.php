@extends('layouts.menu')
@section('content')
    <div class="d-flex mx-5">
        <div class="w-25">
            <ul class="list-group border-dark">
                <li onclick="location.href='/storage/documents/productIn'" class="list-group-item border-dark {{(isset($PDFtype) and $PDFtype == 'productIn') ? 'active' : ''}}">Termék bevételek</li>
                <li onclick="location.href='/storage/documents/productOut'" class="list-group-item border-dark {{(isset($PDFtype) and $PDFtype == 'productOut') ? 'active' : ''}}">Termék kiadások</li>
                <li onclick="location.href='/storage/documents/inventory'" class="list-group-item border-dark {{(isset($PDFtype) and $PDFtype == 'inventory') ? 'active' : ''}}">Leltározás</li>
                <li onclick="location.href='/storage/documents/forStore'" class="list-group-item border-dark {{(isset($PDFtype) and $PDFtype == 'forStore') ? 'active' : ''}}">Bolti kiadás</li>
                <li onclick="location.href='/storage/menu'" class="bg-danger text-white list-group-item border-dark">Vissza a menübe</li>
            </ul>
        </div>
        <div class="w-75 bg-white rounded-end">
            <form action="/storage/documents/getByDate/{{isset($PDFtype) ? $PDFtype : ''}}" method="post">
                @csrf
                <div class="row m-3">
                    <div class="col-md-4">
                        <input type="date" id="startDate" class="form-control border-dark" name="startDate" value="{{isset($startDate) ? $startDate : ''}}">
                    </div>
                    <div class="col-md-4">
                        <input type="date" id="endDate" class="form-control border-dark" name="endDate" value="{{isset($endDate) ? $endDate : ''}}">
                    </div>
                    <div class="col-md-3">
                        @if(isset($PDFtype))
                            <input type="hidden" name="PDFtype" value="{{$PDFtype}}">
                        @endif
                        <input class="form-control btn btn-primary" type="submit" value="Szűrés">
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-danger mx-1" href="/storage/documents/{{isset($PDFtype) ? $PDFtype : ''}}">Törlés</a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="my-2">
                @yield('PDFcontent')
            </div>
        </div>
    </div>
    <script>
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        startDate.addEventListener('change', function() {
            if (startDate.value == null) {
                endDate.min = null;
            } else {
                endDate.min = startDate.value;
            }
        });
        endDate.addEventListener('change', function() {
            if (endDate.value == null) {
                startDate.max = null;
            } else {
                startDate.max = endDate.value;
            }
        });
    </script>
@endsection
