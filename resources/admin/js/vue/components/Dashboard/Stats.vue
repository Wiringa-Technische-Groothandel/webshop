<template>
    <div id="stats">
        <div class="container-fluid" id="before-content">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <table class="header-stat">
                                <tbody>
                                <tr>
                                    <td class="icon" rowspan="2"><i class="fal fa-2x fa-book"></i></td>
                                    <th class="title">Orders</th>
                                </tr>
                                <tr>
                                    <td class="value">{{ orderCount.toLocaleString() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <table class="header-stat">
                                <tbody>
                                <tr>
                                    <td class="icon" rowspan="2"><i class="fal fa-2x fa-user"></i></td>
                                    <th class="title">Gebruikers</th>
                                </tr>
                                <tr>
                                    <td class="value">{{ customerCount.toLocaleString() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <table class="header-stat">
                                <tbody>
                                <tr>
                                    <td class="icon" rowspan="2"><i class="fal fa-2x fa-archive"></i></td>
                                    <th class="title">Producten</th>
                                </tr>
                                <tr>
                                    <td class="value">{{ productCount.toLocaleString() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <table class="header-stat">
                                <tbody>
                                <tr>
                                    <td class="icon" rowspan="2"><i class="fal fa-2x fa-percent"></i></td>
                                    <th class="title">Kortingen</th>
                                </tr>
                                <tr>
                                    <td class="value">{{ discountCount.toLocaleString() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .header-stat {
        width: 100%;

        .icon {
            text-align: center;
            padding: 0 5px;
        }

        .title {
            font-size: 15px;
        }

        .value {
            font-size: 30px;
        }
    }
</style>

<script>
    export default {
        data () {
            return {
                orderCount: 0,
                customerCount: 0,
                productCount: 0,
                discountCount: 0,
            }
        },
        methods: {
            getStats () {
                this.$http.get(route('admin.api.dashboard-stats'))
                    .then((response) => {
                        this.orderCount = response.data.orderCount;
                        this.customerCount = response.data.customerCount;
                        this.productCount = response.data.productCount;
                        this.discountCount = response.data.discountCount;
                    })
                    .catch((error) => {
                        console.error(error);

                        this.$root.$emit('send-notify', {
                            text: "Er is een error opgetreden tijdens het ophalen van de statistieken.",
                            success: false
                        });
                    });
            }
        },
        mounted () {
            this.getStats();
        }
    }
</script>