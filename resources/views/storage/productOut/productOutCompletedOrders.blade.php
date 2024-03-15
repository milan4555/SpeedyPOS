@extends('layouts.menu')
@section('content')
    <div class="m-3 p-3 border border-dark rounded-3 bg-white">
        <div class="text-center pb-3">
            <a href="/storage/productOut/selector" class="btn btn-warning">Váltás rendelésekre</a>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row justify-content-md-center">
                @foreach($orderNumbers as $orderNumber)
                    @php($orderInfo = \App\Http\Controllers\ProductOutController::getOrderInfo($orderNumber->orderNumber))
                    @php($pdfInfo = \App\Http\Controllers\FilePathController::getFileInfo('productOut', $orderNumber->orderNumber))
                    <div class="col-auto mt-4">
                        <div class="card border-dark" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">{{$orderNumber->orderNumber}}. rendelés</h5>
                                <hr class="bg-dark">
                                <p class="card-text">
                                    <b>Teljes összeg:</b> {{$orderInfo->totalsum}} Ft.<br>
                                    <b>Termékek darabszáma:</b> {{$orderInfo->totalnumberofitems}} db<br>
                                    <b>Rendelés dátum:</b><br>{{$orderInfo->created_at}}<br>
                                    <b>Teljesítés dátum:</b><br>{{$pdfInfo->created_at}}
                                </p>
                                <div class="text-center">
                                    <a href="{{asset($pdfInfo->fileType.'/'.$pdfInfo->fileName)}}" target="_blank" class="btn btn-danger">PDF megtekintés</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
