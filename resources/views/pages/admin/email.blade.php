@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <div class="card card-2">
                    @include('components.admin.email.stats')
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card card-2">
                    @include('components.admin.email.test-email')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('document_end')
    <script>
        var mailgun_stats = $.parseJSON('{!! $mailstats !!}');
        var $periodSelector = $('#period-selector');
        var colors = {
            info: '#00BCD4',
            success: '#8BC34A',
            danger: '#F44336'
        };

        var mailgunStatsChart = makeChart(
            document.getElementById('mailgun-stats-chart'),
            'bar',
            {
                labels: [],
                datasets: [
                    {
                        label: "Geaccepteerd",
                        backgroundColor: colors.info,
                        borderColor: colors.info,
                        hoverBackgroundColor: colors.info,
                        hoverBorderColor: colors.info,
                        data: []
                    },
                    {
                        label: "Afgeleverd",
                        backgroundColor: colors.success,
                        borderColor: colors.success,
                        hoverBackgroundColor: colors.success,
                        hoverBorderColor: colors.success,
                        data: []
                    },
                    {
                        label: "Afgewezen",
                        backgroundColor: colors.danger,
                        borderColor: colors.danger,
                        hoverBackgroundColor: colors.danger,
                        hoverBorderColor: colors.danger,
                        data: []
                    }
                ]
            },
            {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        );

        function getMailgunStats() {
            $.ajax({
                url: '{{ route('admin.email::stats') }}',
                method: 'get',
                data: { period: $periodSelector.val() },
                dataType: 'json',
                success: function (data) {
                    parseStats(data);
                }
            });
        }

        function parseStats(data) {
            var stats = data.stats;
            var label;

            mailgunStatsChart.data.labels = [];
            mailgunStatsChart.data.datasets[0].data = [];
            mailgunStatsChart.data.datasets[1].data = [];
            mailgunStatsChart.data.datasets[2].data = [];

            for (var n = 0; n < stats.length; n++) {
                var date = new Date(Date.parse(stats[n].time));

                var hour = (date.getHours() < 10 ? "0" + date.getHours() : date.getHours()) + ":00";
                var day = (date.getDay()+1);
                var month = window.months[date.getMonth()] + " " + date.getFullYear();

                if (data.resolution === "hour") {
                    label = day + " " + month + " " + hour;
                } else if (data.resolution === "day") {
                    label = day + " " + month;
                } else if (data.resolution === "month") {
                    label = month;
                }

                mailgunStatsChart.data.labels.push(label);
                mailgunStatsChart.data.datasets[0].data.push(stats[n].accepted.outgoing);
                mailgunStatsChart.data.datasets[1].data.push(stats[n].delivered.total);
                mailgunStatsChart.data.datasets[2].data.push(stats[n].failed.permanent);
            }

            mailgunStatsChart.update();
        }

        $periodSelector.on('change', getMailgunStats);

        $(document).ready(function () {
            setInterval(getMailgunStats, 10000);
            parseStats(mailgun_stats);
        });
    </script>
@endsection