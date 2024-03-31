<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <title>Leltárazás</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif;}
        thead {font-size: 14px}
        tbody, p {font-size: 14px}
        hr {background-color: black}
    </style>
</head>
<body>
    <h3 class="text-center">Leltározás formanyomtatvány</h3>
    <hr>
    <p>Raktárhelység neve: {{$storageUnitInfo->storageName}}<br>
    Dokumentumot létrehozta: {{$userInfo->firstName}} {{$userInfo->lastName}} (06{{$userInfo->phoneNumber}})<br>
    Elvégzés dátuma: {{date('Y.m.d')}}
    </p>
    <hr>
    <h5 class="text-center">Hiányzó készlet listája</h5>
    <hr>
    @if($notFoundCount == 0)
        <h6 class="text-center">Nincsen megjelenítheto adat!</h6>
    @else
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Cikkszám</th>
                <th>Termék neve</th>
                <th>Darabszám</th>
                <th>Eltérés</th>
                <th>Helyváltoztatás</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                @if(!$product->isFound or $product->quantityDiff != 0)
                    <tr>
                        <th>{{$product->productId}}-{{$product->index}}</th>
                        <td>{{$product->productName}}</td>
                        <td>{{$product->howMany}} db</td>
                        <td>{{!$product->isFound ? -$product->howMany : $product->quantityDiff}} db</td>
                        <td>{{$product->oldStoragePlace == null ? 'Nem' : ($product->oldStoragePlace.'->'.$product->storagePlace)}}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif
    <hr>
    <h5 class="text-center">Leltározott készlet listája</h5>
    <hr>
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Termék neve</th>
            <th>Darabszám</th>
            <th>Eltérés</th>
            <th>Helyváltoztatás</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            @if($product->isFound)
                <tr>
                    <th>{{$product->productId}}-{{$product->index}}</th>
                    <td>{{$product->productName}}</td>
                    <td>{{$product->howMany}} db</td>
                    <td>{{$product->quantityDiff}} db</td>
                    <td>{{$product->oldStoragePlace == null ? 'Nem' : ($product->oldStoragePlace.'->'.$product->storagePlace)}}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</body>
</html>
