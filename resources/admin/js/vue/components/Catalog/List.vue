<template>
    <div id="product-list" class="card">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-list"></i> Producten

                <button class="btn btn-outline-primary float-right" @click="fetchTableData(true)">
                    <i class="far fa-fw fa-sync"></i> Ververs
                </button>
            </h3>

            <hr />

            <table id="products-table" class="table table-hover" style="width: 100%;"></table>
        </div>
    </div>
</template>

<style>
    #products-table_length {
        display: none;
    }
</style>

<script>
    export default {
        data () {
            return {
                table: null,
                products: [],
                columns: [
                    { title: "SKU", data: 'sku' },
                    { title: "Groep", data: 'group' },
                    { title: "Naam", data: 'name' },
                    { title: "Aangemaakt op", data: 'created_at' },
                    { title: "Gewijzigd op", data: 'updated_at' }
                ]
            }
        },
        methods: {
            async fetchTableData (force = false) {
                if (await this.$store.getters['cache/hasProducts'] && !force) {
                    this.$store.getters['cache/getProducts'].then((products) => {
                        this.products = products;

                        this.updateTable();
                    });

                    return;
                }

                this.$http.get(route('admin.api.products'))
                    .then((response) => {
                        this.products = response.data.products;

                        this.$store.commit('cache/products', { products: response.data.products });

                        this.updateTable();
                    })
                    .catch((error) => {
                        console.error(error);

                        this.$root.$emit('send-notify', {
                            text: error,
                            success: false
                        });
                    });
            },
            updateTable () {
                if (! this.table) {
                    this.initDataTable();
                } else {
                    this.table.clear();
                    this.table.rows.add(this.products).draw();
                }
            },
            async initDataTable () {
                this.table = await $('#products-table').DataTable({
                    data: this.products,
                    columns: this.columns
                });

                $('#products-table tbody').on('click', 'tr', (event) => {
                    const data = this.table.row(event.target).data();

                    this.$emit('click', data);
                });
            }
        },
        mounted () {
            this.fetchTableData();
        }
    }
</script>