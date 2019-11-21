<template>
    <div class="col-12">
        <div class="row cart-footer">
            <div class="col-7 col-sm-9">
                <div class="cart-grand-total-text text-right">
                    <b>Totaalprijs</b><br />
                    <span class="small">Prijs excl. BTW</span>
                </div>
            </div>

            <div class="col-5 col-sm-2">
                <div class="cart-grand-total text-right">
                    <i class="far fa-fw fa-euro-sign"></i> {{ grandTotal }}
                </div>
            </div>
        </div>

        <div class="row cart-footer-buttons">
            <div class="col-12 col-md-4 order-2 order-md-1 mb-3">
                <button class="btn btn-outline-danger d-block d-sm-inline" v-on:click="destroyCart">
                    <i class="fal fa-fw fa-trash-alt"></i> Winkelwagen legen
                </button>
            </div>

            <div class="col-12 col-md-8 mr-auto order-1 order-md-2 mb-3 text-right">
                <div class="btn-group">
                    <a class="btn btn-outline-primary d-block d-sm-inline" :href="continueUrl">
                        <i class="fal fa-fw fa-chevron-left"></i> Verder winkelen
                    </a>

                    <a class="btn btn-outline-success d-block d-sm-inline" :href="nextStepUrl">
                        Volgende stap <i class="fal fa-fw fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['grandTotal', 'continueUrl', 'nextStepUrl'],
        methods: {
            destroyCart () {
                this.$root.$emit('cart-destroy');
            }
        }
    }
</script>