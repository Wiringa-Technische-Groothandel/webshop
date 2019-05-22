<template>
    <div class="col-12">
        <div class="row cart-header">
            <div class="col-5 col-lg-6">
                <div class="text-left">
                    Productnaam
                </div>
            </div>

            <div class="col-2">
                <div class="text-left">
                    Prijs
                </div>
            </div>

            <div class="col-2 col-lg-1">
                <div class="text-right">
                    Aantal
                </div>
            </div>

            <div class="col-2">
                <div class="text-right">
                    Subtotaal
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted: () => {
            console.log('Cart header component mounted');
        }
    }
</script>