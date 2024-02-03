@extends('cashRegister/cashRegisterTemplate')

@section('mainSpace')
    <style>
        .my-custom-scrollbar {
            position: relative;
            height: 490px;
            overflow: auto;
        }
        .table-wrapper-scroll-y {
            display: block;
        }
        p {
            margin: 0;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 table-wrapper-scroll-y my-custom-scrollbar">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Számlaszám</th>
                            <th>Vásárlás ideje</th>
                            <th>Fizetés módja</th>
                            <th>Típusa/kicsoda</th>
                            <th>Összeg</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipts as $receipt)
                                <tr>
                                    <th><a href="/cashRegister/receiptList/{{$receipt->receiptId}}" style="text-decoration: none">{{$receipt->receiptId}}</a></th>
                                    <td>{{$receipt->created_at}}</td>
                                    <td>{{$receipt->paymentType}}</td>
                                    <td>{{$receipt->isInvoice == true ? "Számla" : 'Nyugta'}}</td>
                                    <td>{{$receipt->sumPrice}} Ft</td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-5 table-wrapper-scroll-y my-custom-scrollbar">
                @if(isset($receiptData))
                    @php($receiptInfo = \App\Models\Receipt::find($receiptData[0]->receiptId))
                    <div class="border border-2 border-dark p-2 mt-1" style="text-transform: uppercase;">
                        <p class="text-center">Cég neve<br>
                        Cég telephely<br>
                        Üzlet neve<br>
                        Üzlet címe<br>
                        Cég adószáma</p>
                        <br>
                        <h5 class="text-center">-----------&ensp;&ensp;NYUGTA&ensp;&ensp;-----------</h5>
                        <br>
                        @foreach($receiptData as $data)
                            <div class="d-flex justify-content-between">
                                <p>{{$data->productName}}</p>
                                <p>{{$data->quantity}}db ({{$data->bPrice}}Ft.)</p>
                                <p>{{$data->quantity*$data->bPrice}}Ft.</p>
                            </div>
                        @endforeach
                        <p>Részösszeg: <b>{{$receiptInfo->sumPrice}} Ft.</b></p>
                        <hr>
                        <p>Készpénz: <b>{{$receiptInfo->paymentType == 'B' ? 'Bankártyás fizetés' : 'TODO'}}</b></p>
                        <p>Visszajáró: <b>{{$receiptInfo->paymentType == 'B' ? '0 Ft.' : 'TODO'}}</b></p>
                        <hr>
                        <p><b>Összesen: {{$receiptInfo->sumPrice}} Ft.</b></p>
                        {{$receiptInfo->paymentType == 'B' ? '' : "..."}}
                        <p>Visszajáró: <b>{{$receiptInfo->paymentType == 'B' ? '0 Ft.' : 'TODO'}}</b></p>
                        <hr>
                        <div class="d-flex justify-content-around">
                            <p>Kezelő: TODO</p>
                            <p>Kassza: {{$receiptInfo->employeeId}}</p>
                            <p>TR.SZÁM: {{$receiptInfo->receiptId}}</p>
                        </div>
                        <br>
                        <p class="text-center">KÖSZÖNJÜK A VÁSÁRLÁST!</p>
                        <br>
                        <div class="d-flex justify-content-around">
                            <p>NYUGTASZÁM:</p>
                            <p>TODO</p>
                        </div>
                        <div class="d-flex justify-content-around">
                            <p>{{date('Y.m.d', strtotime($receiptInfo->created_at))}}</p>
                            <p>{{date('h:m:s', strtotime($receiptInfo->created_at))}}</p>
                        </div>
                        <p></p>
                        <p class="text-center">P 012345678</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
