@extends('cashRegister/cashRegisterTemplate')

@section('mainSpace')
    <style>
        .my-custom-scrollbar {
            position: relative;
            height: 440px;
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
                                <tr class="receiptRows" data-receiptid="{{$receipt->receiptId}}">
                                    <th>{{$receipt->receiptSerialNumber}}</th>
                                    <td>{{$receipt->created_at}}</td>
                                    <td>{{$receipt->paymentType == 'B' ? 'Kártyás' : 'Készpénz'}}</td>
                                    <td>{{$receipt->isInvoice != 0 ? "Számla" : 'Nyugta'}}</td>
                                    <td>{{$receipt->sumPrice}} Ft</td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-5 table-wrapper-scroll-y my-custom-scrollbar">
                @if(isset($receiptData))
                    @php
                        $receiptInfo = \App\Models\Receipt::find($receiptData[0]->receiptId);
                        $handler = \App\Models\User::find($receiptInfo->employeeId);
                    @endphp
                    <div class="border border-2 border-dark p-2 mt-1" style="text-transform: uppercase;">
                        <p class="text-center">{{$variables['companyName']}}<br>
                            {{$variables['companyAddress']}}<br>
                            {{$variables['shopName']}}<br>
                            {{$variables['shopAddress']}}<br>
                            {{$variables['taxNumber']}}</p>
                        <br>
                        <h5 class="text-center">-----------&ensp;&ensp;NYUGTA&ensp;&ensp;-----------</h5>
                        <br>
                        @foreach($receiptData as $data)
                            <div class="d-flex justify-content-between">
                                <p>{{$data->productName}}</p>
                                <p>{{$data->quantity}}db ({{$data->atTimePrice}} Ft.)</p>
                                <p>{{$data->quantity*$data->atTimePrice}} Ft.</p>
                            </div>
                        @endforeach
                        <p>Részösszeg: <b>{{$receiptInfo->sumPrice}} Ft.</b></p>
                        <hr>
                        <p>Készpénz: <b>{{$receiptInfo->paymentType == 'B' ? 'Bankártyás fizetés' : $receiptInfo->sumPrice+$receiptInfo->change. 'FT.'}}</b></p>
                        <p>Visszajáró: <b>{{$receiptInfo->paymentType == 'B' ? '0' : $receiptInfo->change}} Ft.</b></p>
                        <hr>
                        <p><b>Összesen: {{$receiptInfo->sumPrice}} Ft.</b></p>
                        <p>Készpénz: <b>{{$receiptInfo->paymentType == 'B' ? '0' : $receiptInfo->sumPrice+$receiptInfo->change}} Ft.</b></p>
                        <p>Visszajáró: <b>{{$receiptInfo->paymentType == 'B' ? '0' : $receiptInfo->change}} Ft.</b></p>
                        <hr>
                        <div class="d-flex justify-content-around">
                            <p>Kezelő: {{$handler->firstName}} {{$handler->lastName}}<br>
                            Kassza: {{$receiptInfo->employeeId}}<br>
                            TR.SZÁM: {{$receiptInfo->receiptId}}</p>
                        </div>
                        <br>
                        <p class="text-center">KÖSZÖNJÜK A VÁSÁRLÁST!</p>
                        <br>
                        <div class="d-flex justify-content-around">
                            <p>NYUGTASZÁM: {{$receiptInfo->receiptSerialNumber}}</p>
                            <p></p>
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

@section('buttons')
    <h4 class="text-center">Szűrési feltételek</h4>
    <hr>
    <form id="receiptListForm" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Fizetés típusa:</label>
                <select id="paymentTypeSelect" class="form-control border-dark" name="paymentType">
                    <option value="">Válassz fizetési típust...</option>
                    <option value="K" {{(isset($paymentType) and $paymentType == 'K') ? 'selected' : ''}}>Készpénz</option>
                    <option value="B" {{(isset($paymentType) and $paymentType == 'B') ? 'selected' : ''}}>Bankkártya</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nyugta típusa:</label>
                <select id="receiptType" class="form-control border-dark" name="receiptType">
                    <option value="">Válassz típust...</option>
                    <option value="NY" {{(isset($receiptType) and $receiptType == 'NY') ? 'selected' : ''}}>Nyugta</option>
                    <option value="SZ" {{(isset($receiptType) and $receiptType == 'SZ') ? 'selected' : ''}}>Számla</option>
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
                <label class="form-label">Kezdő dátum:</label>
                <input type="date" class="form-control border-dark" id="startDate" name="startDate" value="{{(isset($startDate) and $startDate != null) ? $startDate : ''}}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Vég dátum:</label>
                <input type="date" class="form-control border-dark" id="endDate" name="endDate" value="{{(isset($endDate) and $endDate != null) != null ? $endDate : ''}}">
            </div>
        </div>
        <input type="hidden" id="shownReceiptId" name="shownReceiptId" value="{{isset($receiptData) ? $receiptData[0]->receiptId : ''}}">
        <div class="d-flex justify-content-center mt-4">
            <input type="submit" class="form-control bg-primary text-white w-25 mx-1" value="Szűrés">
            <a href="/cashRegister/receiptList" class="btn btn-danger w-25 mx-1">Törlés</a>
        </div>
    </form>
    <script>
        const receiptRows = document.getElementsByClassName('receiptRows');
        const shownReceiptId = document.getElementById('shownReceiptId');
        const receiptListForm = document.getElementById('receiptListForm');
        for (let i = 0; i < receiptRows.length; i++) {
            receiptRows[i].addEventListener('click', function () {
                shownReceiptId.value = receiptRows[i].dataset.receiptid;
                receiptListForm.submit();
            });
        }
        const startDate = document.getElementById('startDate');
        const endate = document.getElementById('endDate');
        startDate.addEventListener('change', function () {
            if (startDate.value == null) {
                endate.min = null
            } else {
                endate.min = startDate.value
            }
        });
        endate.addEventListener('change', function () {
            if (endate.value == null) {
                startDate.max = null
            } else {
                startDate.max = endate.value
            }
        });
    </script>
@endsection
