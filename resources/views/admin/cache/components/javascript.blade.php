<script>
    var $memoryChart = document.getElementById('memoryChart');
    var $hitsChart = document.getElementById('hitsChart');

    var memoryChart = new Chart($memoryChart, {
        type: 'doughnut',
        data: {
            labels: ["MB Used", "MB Free", "MB Wasted"],
            datasets: [
                {
                    data: [
                        '{{ $opcache_memory->get('used') }}',
                        '{{ $opcache_memory->get('free') }}',
                        '{{ $opcache_memory->get('wasted') }}'
                    ],
                    backgroundColor: [
                        "#F44336",
                        "#4CAF50",
                        "#FF9800"
                    ],
                    hoverBackgroundColor: [
                        "#FF5252",
                        "#69F0AE",
                        "#FFAB40"
                    ]
                }
            ]
        }
    });

    var hitsChart = new Chart($hitsChart, {
        type: 'doughnut',
        data: {
            labels: ["Misses", "Hits"],
            datasets: [
                {
                    data: [
                        '{{ $opcache_stats->get('misses') }}',
                        '{{ $opcache_stats->get('hits') }}'
                    ],
                    backgroundColor: [
                        "#F44336",
                        "#4CAF50"
                    ],
                    hoverBackgroundColor: [
                        "#FF5252",
                        "#69F0AE"
                    ]
                }
            ]
        }
    });
</script>
