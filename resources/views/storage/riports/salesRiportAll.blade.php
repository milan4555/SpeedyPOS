@extends('storage.riports.riportPageTemplate')
@section('riportPageContent')
    <div class="row">
        <div class="col-md-6">
            <div class="row mt-2">
                <form method="post">
                    @csrf
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
                        <input type="submit" class="btn btn-primary" value="Indítás">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <canvas id="topTenProduct"></canvas>
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
    </div>
    @if(isset($topTenProduct))
        <script>
            const ctx = document.getElementById('topTenProduct');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_column($topTenProduct, 'productId')) ?>,
                    datasets: [{
                        label: 'Árváltozás (Ft.)',
                        data: <?php echo json_encode(array_column($topTenProduct, 'sum')) ?>,
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
    @if(isset($allBoughtPriceSum) and isset($allSoldPriceSum))
        <script>
            const ctx1 = document.getElementById('priceDiagram');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($allBoughtPriceSum)) ?>,
                    datasets: [
                        {
                            label: 'Bevétel (Ft.)',
                            data: <?php echo json_encode($allSoldPriceSum) ?>,
                            borderWidth: 1
                        },
                        {
                            label: 'Kiadás (Ft.)',
                            data: <?php echo json_encode($allBoughtPriceSum) ?>,
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
        @if(isset($allBought) and isset($allSold))
            <script>
                const ctx2 = document.getElementById('soldItems');
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_keys($allBought)) ?>,
                        datasets: [
                            {
                                label: 'Termékek kiadás (DB)',
                                data: <?php echo json_encode($allSold) ?>,
                                borderWidth: 1
                            },
                            {
                                label: 'Termékek bevétel (DB)',
                                data: <?php echo json_encode($allBought) ?>,
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
