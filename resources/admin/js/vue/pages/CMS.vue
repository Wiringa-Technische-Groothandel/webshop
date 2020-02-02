<template>
    <div class="container-fluid" id="page-cms">
        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <div class="card card-2">
                    <div class="card-body">
                        <block-manager></block-manager>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card card-2">
                    <div class="card-body">
                        <description-manager></description-manager>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BlockManager from '../components/CMS/BlockManager'
    import DescriptionManager from '../components/CMS/DescriptionManager'

    export default {
        components: {
            BlockManager,
            DescriptionManager
        }
    }
</script>