<template>
    <div class="card" id="order-chart">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-book"></i> Orders per maand

                <select v-model="selectedYear" class="custom-select d-inline-block w-auto float-right pl-2" @change="getChartData" v-if="years.length > 0">
                    <option v-for="year in selectableYears" :value="year">{{ year }}</option>
                </select>
            </h3>

            <hr />

            <div style="height: 300px;" v-if="years.length > 0">
                <canvas id="order-chart-canvas"></canvas>
            </div>

            <p v-else>Geen data om weer te geven.</p>
        </div>
    </div>
</template>

<script>
    import Chart from '../../../services/chart'

    export default {
        props: {
            chartDataUrl: {
                required: false,
                type: String,
                default () {
                    return route('admin.api.order-chart')
                }
            },
            years: {
                required: false,
                type: Array,
                default () {
                    return [
                        (new Date).getFullYear()
                    ]
                }
            },
            months: {
                required: false,
                type: Array,
                default () {
                    return ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni',
                        'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
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
                selectableYears: this.years,
                selectedYear: 2019
            }
        },
        methods: {
            createChart () {
                this.chart = new Chart(document.getElementById('order-chart-canvas'), {
                    type: 'bar',
                    data: {
                        labels: this.months,
                        datasets: [
                            {
                                label: "Orders",
                                backgroundColor: this.defaultChartColor,
                                borderColor: this.defaultChartColor,
                                hoverBackgroundColor: this.defaultChartColor,
                                hoverBorderColor: this.defaultChartColor,
                                data: [0,0,0,0,0,0,0,0,0,0,0,0]
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
                        year: this.selectedYear
                    }
                }).then((response) => {
                    const data = response.data.chartData;

                    this.selectableYears = response.data.years;

                    let chartData = [0,0,0,0,0,0,0,0,0,0,0,0];
                    let x, i;

                    for (i = 0; i < data.length; i++) {
                        // Replace the data from the empty array with data from the ajax response
                        chartData[data[i].month-1] = data[i].count;
                    }

                    for (x = 0; x < 12; x++) {
                        this.chart.data.datasets[0].data[x] = chartData[x];
                    }

                    this.chart.update();
                }).catch((e) => {
                    console.error(e);

                    console.log('Failed to load order chart data.');
                });
            }
        },
        mounted () {
            this.createChart();
        }
    }
</script>