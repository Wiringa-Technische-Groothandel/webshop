<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-file-alt"></i> {{ __('Bestellingen') }}
        </h3>

        <hr />

        <div style="height: 300px;">
            <canvas id="order-chart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        console.log({{ $orders->keys() }});
        console.log({{ $orders->values() }});
        $(document).ready(function () {
            // Get the context of the canvas element we want to select
            var orderChart = makeChart(
                document.getElementById('order-chart'),
                'bar',
                {
                    labels: {{ $orders->keys() }},
                    datasets: [
                        {
                            label: "{{ __('Bestellingen') }}",
                            backgroundColor: '#333333',
                            borderColor: '#333333',
                            hoverBackgroundColor: '#333333',
                            hoverBorderColor: '#333333',
                            data: {{ $orders->values() }}
                        }
                    ]
                },
                {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                userCallback: function(label, index, labels) {
                                    // when the floored value is the same as the value we have a whole number
                                    if (Math.floor(label) === label) {
                                        return label;
                                    }

                                },
                            }
                        }]
                    }
                }
            );
        });
    </script>
@endpush