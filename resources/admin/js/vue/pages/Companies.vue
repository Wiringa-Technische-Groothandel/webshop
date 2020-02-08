<template>
    <div id="page-companies">
        <template v-if="!selectedCompany">
            <company-create-modal :show="showModal" @company-created="showCompany"
                                  @close="showModal = false" @hide="showModal = false"></company-create-modal>
        </template>

        <button class="btn btn-success bmd-btn-fab tooltip-toggle" data-toggle="modal" v-if="!selectedCompany"
                @click="showModal = true" title="Debiteur toevoegen">
            <i class="fal fa-fw fa-plus"></i>
        </button>

        <div class="container-fluid">
            <div class="row mb-3" v-if="!selectedCompany">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>
                                <i class="fal fa-fw fa-building"></i> Bedrijven
                            </h3>

                            <hr />

                            <company-list @click="showCompany"></company-list>
                        </div>
                    </div>
                </div>
            </div>

            <template v-else>
                <company-details :company-id="selectedCompany" @company-updated="triggerListUpdate"></company-details>
            </template>
        </div>
    </div>
</template>

<style scoped>
    .bmd-btn-fab {
        position: fixed !important;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
</style>

<script>
    import CompanyCreateModal from '../components/Companies/CreateModal'
    import CompanyList from '../components/Companies/List'
    import CompanyDetails from '../components/Companies/Details'

    export default {
        data () {
            let companyId = this.$route.query.id;

            return {
                showModal: false,
                selectedCompany: companyId
            }
        },
        watch: {
            $route(to, from) {
                if (this.selectedCompany === to.query.id) {
                    return;
                }

                this.selectedCompany = to.query.id;
            }
        },
        methods: {
            triggerListUpdate () {
                this.$root.$emit('update-company-table');
            },
            showCompany (companyId) {
                let query = {
                    id: companyId
                };

                if (this.$route.query.page) {
                    query.page = this.$route.query.page;
                }

                this.$router.push({ query });

                this.selectedCompany = companyId;
            }
        },
        components: {
            CompanyCreateModal,
            CompanyList,
            CompanyDetails
        },
        mounted () {
            $('.tooltip-toggle').tooltip();
        }
    }
</script>