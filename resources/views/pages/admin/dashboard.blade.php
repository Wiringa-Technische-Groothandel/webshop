@extends('layouts.admin')

@section('title', __('Dashboard'))

@section('content')
    @include('components.admin.dashboard.stats')

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        @include('components.admin.dashboard.order-chart')
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        @include('components.admin.dashboard.import-data')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .header-stat {
            width: 100%;
        }

        .header-stat .icon {
            text-align: center;
            padding: 0 5px;
        }

        .header-stat .title {
            font-size: 15px;
        }

        .header-stat .value {
            font-size: 30px;
        }
    </style>
@endpush

@push('scripts')
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
@endpush