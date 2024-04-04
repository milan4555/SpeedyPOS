@extends('layouts.menu')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="bg-white mx-4 border">
        <div class="row mx-2">
            <div class="col-sm-3 rounded-start-3 h-100">
                <ul class="list-group border-dark">
                    <li onclick="location.href='/storage/riportPage/salesRiport';" class="list-group-item">Eladási riportok (termék)</li>
                    <li onclick="location.href='/storage/riportPage/salesRiportAll'" class="list-group-item">Eladási riportok (teljes)</li>
                </ul>
            </div>
            <div class="col-sm-9 border border-dark border-3">
                @yield('riportPageContent')
            </div>
        </div>
    </div>
@endsection
