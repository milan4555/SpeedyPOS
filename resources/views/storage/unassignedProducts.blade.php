@extends('layouts.menu')
@section('content')
    <div class="m-3 p-3 border border-dark rounded-3 bg-white">
        <div class="d-flex justify-content-center pb-3">
            <button onclick="window.location.href = '/storage/menu'" class="btn button-red">Vissza a menübe</button>
            <input id="unassignedSearchInput" onkeyup="unassignedLiveSearch()" class="form-control border-dark w-25 mx-2" placeholder="Kereső...">
        </div>
        <div class="d-flex table-responsive justify-content-center" style="height: 500px">
            <table id="unassignedTable" class="table border border-dark">
                <thead class="table-dark">
                    <tr>
                        <th>Cikkszám</th>
                        <th>Termék neve</th>
                        <th>Mennyiség</th>
                        <th>Raktári helye</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($products) > 0)
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->productId}}-{{$product->index}}</td>
                            <td>{{$product->productName}}</td>
                            <td>{{$product->howMany}} db</td>
                            <td>
                                <input class="form-control border-dark" type="text" id="{{$product->productId}}-{{$product->index}}" name="assignedStoragePlace">
                            </td>
                        </tr>
                        <script>
                            const productStorageInput = document.getElementById('{{$product->productId}}-{{$product->index}}');
                            productStorageInput.addEventListener('keypress', function (event) {
                                if (event.key === 'Enter') {
                                    window.location.href = '/storage/assignProduct/' + productStorageInput.id + '/' + productStorageInput.value
                                }
                            });
                        </script>
                    @endforeach
                @else
                    <tr class="align-middle">
                        <td colspan="4"><h1 class="text-center mx-auto p-5">Nincsenek olyan termékek, amelyek elhelyezésre várnak a raktárban!</h1></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function unassignedLiveSearch() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("unassignedSearchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("unassignedTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
