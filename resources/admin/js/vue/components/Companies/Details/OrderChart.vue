<template>
    <div id="order-chart">
        <h3>
            <i class="fal fa-fw fa-book"></i> Orders per jaar
        </h3>

        <hr />

        <div style="height: 300px;">
            <canvas id="company-order-chart-canvas"></canvas>
        </div>
    </div>
</template>

<script>
    import Chart from '../../../../services/chart'

    export default {
        props: {
            companyId: {
                required: true,
                type: Number
            },
            chartDataUrl: {
                required: false,
                type: String,
                default () {
                    return route('admin.api.company-order-chart')
                }
            },
            defaultChartColor: {
                required: false,
                type: String,
                default: '#2196F3'
            }
        },
        data () {
            return {
                chart: null,
            }
        },
        methods: {
            createChart () {
                this.chart = new Chart(document.getElementById('company-order-chart-canvas'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: "Orders",
                                backgroundColor: this.defaultChartColor,
                                borderColor: this.defaultChartColor,
                                hoverBackgroundColor: this.defaultChartColor,
                                hoverBorderColor: this.defaultChartColor,
                                data: []
                            }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true,
                                    max: 70
                                }
                            }]
                        }
                    }
                });

                this.getChartData();
            },
            getChartData () {
                this.$http.get(this.chartDataUrl, {
                    params: {
                        id: this.companyId
                    }
                }).then((response) => {
                    let chartData = [];
                    let chartLabels = [];

                    response.data.chartData.forEach((item) => {
                        chartData.push(item.count);
                        chartLabels.push(item.year);
                    });

                    this.chart.data.labels = chartLabels;
                    this.chart.data.datasets[0].data = chartData;

                    this.chart.update();
                }).catch((error) => {
                    console.error(error);

                    console.log('Failed to load order chart data.');
                });
            }
        },
        mounted () {
            this.createChart();
        }
    }
</script>