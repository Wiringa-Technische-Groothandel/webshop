<template>
    <div class="prices" :class="{ 'price-loading': fetching, 'price-loaded': !fetching }">
        <template v-if="fetching && loggedIn">
            <div class="loading-animation text-center">
                Uw persoonlijke prijs wordt opgehaald <br/>
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
            <template v-if="action">
                <div class="action-price">
                    Actieprijs:
                    <span class="d-block d-sm-inline">
                        <i class="fas fa-euro-sign"></i> <span>{{ formatPrice(netPrice) }}</span>
                    </span>
                </div>
            </template>

            <template v-else-if="netPrice && showSinglePrice">
                <div class="net-price">
                    Bruto / Netto:
                    <span class="d-block d-sm-inline">
                        <i class="fas fa-euro-sign"></i> <span>{{ formatPrice(netPrice) }}</span>
                    </span>
                </div>
            </template>

            <template v-else-if="netPrice && grossPrice">
                <div class="gross-price">
                    Bruto:
                    <span class="d-block d-sm-inline">
                        <i class="fas fa-euro-sign"></i> <span>{{ formatPrice(calculatePrice(grossPrice)) }}</span>
                    </span>
                </div>

                <div class="net-price">
                    Netto:
                    <span class="d-block d-sm-inline">
                        <i class="fas fa-euro-sign"></i> <span>{{ formatPrice(calculatePrice(netPrice)) }}</span>
                    </span>
                </div>
            </template>
        </template>

        <template v-if="!fetching && loggedIn">
            <small class="form-text text-muted price-per">
                {{ product.price_per_str }}
            </small>

            <small class="form-text text-muted stock" v-html="product.stock_status"></small>
        </template>
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
        data() {
            return {
                fetching: true,
                error: false,
                netPrice: false,
                grossPrice: false,
                pricePer: false,
                action: false
            }
        },
        computed: {
            showSinglePrice() {
                return !this.action &&
                    this.netPrice === this.grossPrice &&
                    (this.product.price_factor && this.product.price_factor.price_unit === 'DAG')
            }
        },
        methods: {
            calculatePrice(price) {
                return (price * this.product.price_factor.price_factor) / this.pricePer;
            },
            formatPrice(price) {
                return price.toFixed(2)
            }
        },
        created() {
            if (this.loggedIn) {
                this.$root.$emit('fetch-price', this.product.sku);

                const timeout = setTimeout(() => {
                    this.fetching = false;
                    this.error = true;
                }, 5000);

                this.$root.$on('price-fetched-' + this.product.sku, data => {
                    clearTimeout(timeout);

                    this.fetching = false;
                    this.netPrice = data.netPrice;
                    this.grossPrice = data.grossPrice;
                    this.pricePer = data.pricePer;
                    this.action = data.action;
                });
            } else {
                this.fetching = false;
            }
        }
    }
</script>
