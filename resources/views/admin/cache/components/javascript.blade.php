<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    function resetCache () {
        if (! confirm('{{ __('Weet u zeker dat u de cache wilt legen?') }}')) {
            return;
        }

        axios.delete('')
            .then(() => {
                window.location.reload();
            })
            .catch((error) => {
                alert(error);
            });
    }

    let $memoryChart = document.getElementById('memoryChart');
    let $hitsChart = document.getElementById('hitsChart');

    let memoryChart = new Chart($memoryChart, {
        type: 'doughnut',
        data: {
            labels: ["In gebruik", "Vrij"],
            datasets: [
                {
                    data: [
                        '{{ $opcache_memory->get('used') + $opcache_memory->get('wasted') }}',
                        '{{ $opcache_memory->get('free') }}',
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

    let hitsChart = new Chart($hitsChart, {
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
