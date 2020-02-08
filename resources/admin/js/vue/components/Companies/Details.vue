<template>
    <div id="company-details">
        <div class="row mb-3" v-if="company">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-default" @click="goToOverview">
                            <i class="fal fa-fw fa-chevron-left"></i> Terug naar overzicht
                        </button>

                        <h3 class="d-inline-block float-right">{{ company.customer_number }} - {{ company.name }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="company">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <company-details :company="company" :password="password"
                                     @save="companySaved" @change="companyChanged"></company-details>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <order-chart :company-id="company.id"></order-chart>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-12">
                    <company-accounts :customers="company.customers"></company-accounts>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import CompanyDetails from './Details/Company'
    import CompanyAccounts from './Details/Accounts'
    import OrderChart from "./Details/OrderChart";

    export default {
        components: {
            OrderChart,
            CompanyDetails,
            CompanyAccounts
        },
        props: {
            companyId: {
                required: true,
                type: [Number,String]
            }
        },
        data () {
            return {
                company: null,
                password: null
            }
        },
        methods: {
            companyChanged (company) {
                this.fetchCompany();
            },
            companySaved (company) {
                this.$http.post(route('admin.api.update-company'), company)
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });

                        this.fetchCompany();
                    })
                    .catch(this.handleError);
            },
            goToOverview () {
                let query = {};

                if (this.$route.query.page) {
                    query.page = this.$route.query.page;
                }

                this.$router.push({
                    path: '/companies',
                    query
                });
            },
            async fetchCompany () {
                await this.$http.get(route('admin.api.fetch-company'), {
                    params: {
                        id: this.companyId
                    }
                })
                    .then((response) => {
                        this.company = response.data.company;
                        this.password = response.data.initialPassword;
                    })
                    .catch(this.handleError);
            },
            handleError (error) {
                console.error(error);

                this.$root.$emit('send-notify', {
                    text: error,
                    success: false
                });
            }
        },
        mounted() {
            this.fetchCompany();
        }
    }
</script>
