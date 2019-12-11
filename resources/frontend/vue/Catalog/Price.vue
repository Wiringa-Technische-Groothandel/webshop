<template>
    <div class="prices" :class="{ 'price-loading': fetching, 'price-loaded': !fetching }">
        <template v-if="fetching">
            <div class="loading-animation text-center">
                Uw prijs wordt opgehaald <br />
                <i class="fal fa-sync fa-spin"></i>
            </div>
        </template>

        <div v-if="! loggedIn">
            <a :href="authUrl" onclick="event.preventDefault()" data-toggle="modal"
               data-target="#loginModal">Log in</a> om uw persoonlijke prijs te bekijken.
        </div>

        <div class="action-price" v-if="action">
            Actieprijs:
            <span class="d-block d-sm-inline">
                <i class="fas fa-euro-sign"></i> <span>{{ grossPrice }}</span>
            </span>
        </div>

        <div class="gross-price" v-if="grossPrice !== false && !action">
            Bruto:
            <span class="d-block d-sm-inline">
                <i class="fas fa-euro-sign"></i> <span>{{ grossPrice }}</span>
            </span>
        </div>

        <div class="net-price" v-if="netPrice !== false && !action">
            Netto:
            <span class="d-block d-sm-inline">
                <i class="fas fa-euro-sign"></i> <span>{{ netPrice }}</span>
            </span>
        </div>

        <small class="form-text text-muted price-per" v-if="pricePer !== false">
            {{ pricePer }}
        </small>

        <small class="form-text text-muted stock" v-if="stock !== false" v-html="stock"></small>
    </div>
</template>

<script>
    export default {
        props: {
            'product': {
                type: Object,
                required: true
            },
            'logged-in': {
                type: Boolean,
                required: true
            },
            'auth-url': {
                type: String,
                required: true
            }
        },
        data () {
            return {
                fetching: true,
                netPrice: false,
                grossPrice: false,
                pricePer: false,
                stock: false,
                action: false
            }
        },
        created () {
            if (this.loggedIn) {
                this.$root.$emit('fetch-price', this.product.sku);

                this.$root.$on('price-fetched-' + this.product.sku, (data) => {
                    this.$data.fetching = false;
                    this.$data.netPrice = data.netPrice.toFixed(2);
                    this.$data.grossPrice = data.grossPrice.toFixed(2);
                    this.$data.pricePer = data.pricePer;
                    this.$data.stock = data.stock;
                    this.$data.action = data.action;
                });
            } else {
                this.$data.fetching = false;
            }
        }
    }
</script>