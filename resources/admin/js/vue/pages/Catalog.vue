<template>
    <div class="container-fluid" id="page-catalog">
        <div class="row mb-3">
            <div class="col-sm-9">
                <product-list @click="setSku"></product-list>
            </div>

            <div class="col-sm-3">
                <sync-product :sku="sku"></sync-product>

                <product-indexer></product-indexer>
            </div>
        </div>
    </div>
</template>

<script>
    import ProductList from '../components/Catalog/List'
    import ProductIndexer from '../components/Catalog/Indexer'
    import SyncProduct from '../components/Catalog/SyncProduct'

    export default {
        components: {
            ProductList,
            ProductIndexer,
            SyncProduct
        },
        data () {
            return {
                sku: ''
            }
        },
        methods: {
            setSku (product) {
                this.sku = product.sku;
            }
        }
    }
</script>