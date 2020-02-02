<template>
    <div id="page-dashboard">
        <stats></stats>

        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-8">
                    <order-chart></order-chart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Stats from '../components/Dashboard/Stats'
    import OrderChart from '../components/Dashboard/OrderChart'

    export default {
        components: {
            Stats,
            OrderChart
        }
    }
</script>