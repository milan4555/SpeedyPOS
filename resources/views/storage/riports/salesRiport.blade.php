@extends('storage.riports.riportPageTemplate')
@section('riportPageContent')
    <div class="row">
        <div class="col-md-6">
            <div class="row mt-2">
               <form method="post">
                   @csrf
                   <select id="productIdSelector" class="form-control border-dark mt-1" name="productId" required>
                       <option value="">Válassz termékkódot!</option>
                       @foreach($allProduct as $product)
                           <option value="{{$product->productId}}"
                               {{$product->productId == (isset($selectedProductId) ? $selectedProductId : '') ? 'selected' : ''}}
                           >{{$product->productId}} ({{$product->productName}})</option>
                       @endforeach
                   </select>
                   <div class="col-md-6 mt-2">
                       <label for="startDate">Kezdődátum:</label>
                       <input class="form-control border-dark" id="startDate" type="month" name="startDate"
                              value="{{isset($startDate) ? $startDate : ''}}" required>
                   </div>
                   <div class="col-md-6 mt-2">
                       <label for="endDate">Végdátum:</label>
                       <input class="form-control border-dark" id="endDate" type="month" name="endDate"
                              value="{{isset($endDate) ? $endDate : ''}}" required>
                   </div>
                   <div class="col-md-6 mt-2">
                       <input type="submit" class="btn button-blue" value="Indítás">
                   </div>
               </form>
            </div>
        </div>
        @if(!isset($sumQuantity))
            <div class="col-md-6 align-middle"><h2>A riport elkezdéséhez válaszd ki a megfelelő szűrőket!</h2></div>
        @else
        <div class="col-md-6">
            <div>
                <canvas id="priceChange"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <canvas id="priceDiagram"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <canvas id="soldItems"></canvas>
            </div>
        </div>
        @endif
    </div>
    @if(isset($sumQuantity) and isset($quantityBoughtList))
        <script>
            const ctx = document.getElementById('soldItems');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($sumQuantity)) ?>,
                    datasets: [
                        {
                            label: 'Eladott termékek (DB.)',
                            data: <?php echo json_encode($sumQuantity) ?>,
                            borderWidth: 1
                        },
                        {
                            label: 'Vásárolt termékek (DB.)',
                            data: <?php echo json_encode($quantityBoughtList) ?>,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endif
    @if(isset($priceChange))
        <script>
            const ctx1 = document.getElementById('priceDiagram');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_keys($priceChange)) ?>,
                    datasets: [{
                        label: 'Árváltozás (Ft.)',
                        data: <?php echo json_encode($priceChange) ?>,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endif
    @if(isset($sumSales) and isset($sumBought))
        <script>
            const ctx2 = document.getElementById('priceChange');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_keys($sumSales)) ?>,
                    datasets: [
                        {
                            label: 'Bevétel (Ft.)',
                            data: <?php echo json_encode($sumSales) ?>,
                            borderWidth: 1
                        },
                        {
                            label: 'Kiadás (Ft.)',
                            data: <?php echo json_encode($sumBought) ?>,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            });
        </script>
    @endif
    <script>
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        startDate.addEventListener('change', function () {
            const value = startDate.value;
            if (value == '') {
                endDate.min = '';
                endDate.max = '';
            } else {
                endDate.min = value;
                const split = value.split('-')
                const newYear = (parseInt(split[0])+1);
                endDate.max = newYear.toString() + '-' + split[1];
            }
        });
        endDate.addEventListener('change', function () {
            const value = endDate.value;
            if (value == '') {
                startDate.min = '';
                startDate.max = '';
            } else {
                startDate.max = value;
                const split = value.split('-')
                const newYear = (parseInt(split[0])-1);
                startDate.min = newYear.toString() + '-' + split[1];
            }
        });
    </script>
@endsection
