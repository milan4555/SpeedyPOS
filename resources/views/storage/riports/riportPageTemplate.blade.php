@extends('layouts.menu')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="d-flex flex-wrap mx-5">
        <div class="w-25">
            <ul class="list-group border border-dark border-3">
                <li onclick="location.href='/storage/riportPage/salesRiport';" class="list-group-item {{request()->is('storage/riportPage/salesRiport') ? 'active' : ''}}">Eladási riportok (termék)</li>
                <li onclick="location.href='/storage/riportPage/salesRiportAll'" class="list-group-item {{request()->is('storage/riportPage/salesRiportAll') ? 'active' : ''}}">Eladási riportok (teljes)</li>
                <li onclick="location.href='/storage/menu'" class="list-group-item bg-danger text-white">Vissza a menübe</li>
            </ul>
        </div>
        <div class="w-75 bg-white border border-dark border-3 rounded p-2">
            @if(request()->is('storage/riportPage'))
                <h2 class="text-center pt-2">A riportok megtekintéséhez válassz egy menüpontot az oldalsó sávból, majd add meg a keresési szűrőket!</h2>
            @else
                @yield('riportPageContent')
            @endif
        </div>
    </div>
@endsection
