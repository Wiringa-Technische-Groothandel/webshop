@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <div class="card card-2">
                    @include('components.admin.dashboard.order-chart')
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card card-2">
                    @include('components.admin.dashboard.import-data')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('document_end')
    @if ($years->isNotEmpty())
        <script type="text/javascript">
            var chartColors = months.map(randomColor);

            // Get the context of the canvas element we want to select
            var orderChart = makeChart(
                document.getElementById('order-chart'),
                'bar',
                {
                    labels: window.months,
                    datasets: [
                        {
                            label: "Orders",
                            backgroundColor: chartColors,
                            borderColor: chartColors,
                            hoverBackgroundColor: chartColors,
                            hoverBorderColor: chartColors,
                            data: [0,0,0,0,0,0,0,0,0,0,0,0]
                        }
                    ]
                },
                {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                max: 70
                            }
                        }]
                    }
                }
            );

            function getOrderChartData() {
                $.ajax({
                    url: "{{ route('admin.dashboard::chart', [ 'type' => 'orders' ]) }}",
                    type: "GET",
                    data: { year : $('#yearSelect').val() },
                    dataType: "json",
                    success: function(response) {
                        var data = response.payload;
                        var chartData = [0,0,0,0,0,0,0,0,0,0,0,0];
                        var x, i;

                        for (i = 0; i < data.length; i++) {
                            // Replace the data from the empty array with data from the ajax response
                            chartData[data[i].month-1] = data[i].count;
                        }

                        for (x = 0; x < 12; x++) {
                            orderChart.data.datasets[0].data[x] = chartData[x];
                        }

                        orderChart.update();
                    }
                });
            }


            $(document).ready(function() {
                getOrderChartData();
            });
        </script>
    @endif
@endsection