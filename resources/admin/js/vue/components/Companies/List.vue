<template>
    <div id="companies-table">
        <table id="companies-datatable" class="table table-hover" style="width: 100%;"></table>
    </div>
</template>

<style>
    #companies-datatable_length {
        display: none;
    }
</style>

<script>
    export default {
        props: {
            tableData: {
                required: false,
                type: Array,
                default () {
                    return []
                }
            }
        },
        data () {
            return {
                table: null,
                companies: this.tableData,
                columns: [
                    { title: "#", data: 'customer_number' },
                    { title: "Naam", data: 'name' },
                    { title: "Aantal gebruikers", data: 'account_count' },
                    { title: "Aangemaakt op", data: 'created_at' },
                    { title: "Gewijzigd op", data: 'updated_at' }
                ]
            }
        },
        methods: {
            async initDataTable () {
                await this.loadTableData();

                const dataTable = $('#companies-datatable');

                this.table = await dataTable.DataTable({
                    data: this.companies,
                    columns: this.columns
                });

                if (this.$route.query.page) {
                    this.table.page(this.$route.query.page - 1).draw(false);
                }

                dataTable.on('page.dt', () => {
                    const currentPage = this.table.page.info().page + 1;

                    if (currentPage === this.$route.query.page) {
                        return;
                    }

                    this.$router.push({
                        query: {
                            page: currentPage
                        }
                    });
                } );

                dataTable.find('tbody').on('click', 'tr', (event) => {
                    const row = this.table.row(event.target);

                    this.$emit('click', row.data().id);
                });
            },
            loadTableData () {
                return this.$http.get(route('admin.api.companies'))
                    .then((response) => {
                        this.companies = response.data.companies;
                    })
                    .catch((error) => {
                        console.error(error);

                        this.$root.$emit('send-notify', {
                            text: error,
                            success: false
                        });
                    });
            }
        },
        mounted () {
            this.$root.$on('update-company-table', () => {
                this.loadTableData();
            });

            this.initDataTable();
        }
    }
</script>