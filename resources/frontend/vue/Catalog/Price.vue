<template>
    <div class="prices" :class="{ 'price-loading': fetching, 'price-loaded': !fetching }">
        <template v-if="fetching && loggedIn">
            <div class="loading-animation text-center">
                Uw prijs wordt opgehaald <br />
                <i class="fal fa-sync fa-spin"></i>
            </div>
        </template>

        <div v-if="! loggedIn">
            <a :href="authUrl" onclick="event.preventDefault()" data-toggle="modal"
               data-target="#loginModal">Log in</a> om uw persoonlijke prijs te bekijken.
        </div>

        <div class="error" v-if="! fetching && error">
            Prijs onbekend
        </div>

        <template v-if="! error">
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
        </template>

        <small class="form-text text-muted price-per" v-if="! fetching && loggedIn">
            {{ product.price_per_str }}
        </small>

        <small class="form-text text-muted stock" v-if="! fetching && loggedIn" v-html="product.stock_status"></small>
    </div>
</template>

<script>
    export default {
        props: {
            product: {
                type: Object,
                required: true
            },
            loggedIn: {
                type: Boolean,
                required: true
            },
            authUrl: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                fetching: true,
                error: false,
                netPrice: false,
                grossPrice: false,
                action: false
            }
        },
        created () {
            if (this.loggedIn) {
                this.$root.$emit('fetch-price', this.product.sku);

                const timeout = setTimeout(() => {
                    this.fetching = false;
                    this.error = true;
                }, 5000);

                this.$root.$on('price-fetched-' + this.product.sku, data => {
                    clearTimeout(timeout);

                    this.fetching = false;
                    this.netPrice = data.netPrice.toFixed(2);
                    this.grossPrice = data.grossPrice.toFixed(2);
                    this.action = data.action;
                });
            } else {
                this.fetching = false;
            }
        }
    }
</script>
