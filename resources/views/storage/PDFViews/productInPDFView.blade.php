<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <style>
        body { font-family: DejaVu Sans, sans-serif;}
        thead {font-size: 14px}
        tbody, p {font-size: 12px}
        hr {background-color: black}
    </style>
    <title>Termék bevételezés</title>
</head>
<body>
    <h3 class="text-center">Termékbevételezés formanyomtatvány</h3><br>
    <hr>
    <table>
        <tr>
            <td>
                <h5>Beszállító adatai:</h5>
                <p>
                    <b>Név</b>: {{$supplier->companyName}}<br>
                    <b>Telephely címe</b>: {{$supplier->postcode}}, {{$supplier->city}}, {{$supplier->street}} {{$supplier->streetNumber}}<br>
                    <b>Telefonszám</b>: 06{{$supplier->phoneNumber}}<br>
                    <b>Adószám</b>: {{$supplier->taxNumber}}<br>
                    <b>Tulajdonos</b>: {{$supplier->owner}}
                </p>
            </td>
            <td>
            </td>
            <td class="px-5">
                <h5>Saját adatok:</h5>
                <p>
                    <b>Név</b>: {{$supplier->companyName}}<br>
                    <b>Telephely címe</b>: {{$supplier->postcode}}, {{$supplier->city}}, {{$supplier->street}} {{$supplier->streetNumber}}<br>
                    <b>Telefonszám</b>: 06{{$supplier->phoneNumber}}<br>
                    <b>Adószám</b>: {{$supplier->taxNumber}}<br>
                    <b>Tulajdonos</b>: {{$supplier->owner}}
                </p>
            </td>
        </tr>
    </table>
    <hr>
    <h5>Bevételezett áruk listája:</h5>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Cikkszám</th>
                    <th>Termék neve</th>
                    <th>Darabszám</th>
                    <th>Bruttó ár</th>
                    <th>Nettó ár</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $data)
                    <tr>
                        <td>{{$data->productId}}</td>
                        <td>{{$data->productName}}</td>
                        <td>{{$data->howMany}} DB</td>
                        <td>{{$data->bPrice}} Ft.</td>
                        <td>{{$data->nPrice}} Ft.</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    <br>
    <div class="text-center">
        <hr class="w-50">
        Bevételező aláírása
    </div>
    <p>
        <b>Bevételezést végzo kolléga:</b> {{$worker->firstName}} {{$worker->lastName}}<br>
        <b>Elvégzés ideje:</b> {{date('Y.m.d')}}
    </p>
</body>
</html>
