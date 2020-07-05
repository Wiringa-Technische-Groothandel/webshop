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

            <template v-else-if="netPrice && grossPrice">
                <div class="gross-price">
                    Bruto:
                    <span class="d-block d-sm-inline">
                        <i class="fas fa-euro-sign"></i> <span>{{ formatPrice(calculatePrice(grossPrice)) }}</span>
                    </span>
                </div>

                <div class="net-price">
                    Uw prijs:
                    <span class="d-block d-sm-inline">
                        <i class="fas fa-euro-sign"></i> <span>{{ formatPrice(calculatePrice(netPrice)) }}</span>
                    </span>
                </div>
            </template>
        </template>

        <template v-if="!fetching && loggedIn">
            <small class="form-text text-muted price-per">
                {{ pricePerStr }}
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
                action: false,
                priceFactor: false,
                priceUnit: false,
                scaleUnit: false,
            }
        },
        methods: {
            calculatePrice(price) {
                return (price * this.priceFactor) / this.pricePer;
            },
            formatPrice(price) {
                return price.toFixed(2)
            }
        },
        computed: {
            pricePerStr () {
                if (this.priceUnit === 'DAG') {
                    return 'Huurprijs per dag';
                }

                if (this.priceUnit === this.scaleUnit) {
                    return 'Prijs per %1'.replace('%1', this.priceUnit);
                }

                return 'Prijs per %1 van %2 %3'
                    .replace('%1', this.scaleUnit)
                    .replace('%2', this.priceFactor)
                    .replace('%3', this.priceUnit);
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
                    this.priceFactor = data.priceFactor;
                    this.priceUnit = data.priceUnit;
                    this.scaleUnit = data.scaleUnit;
                });
            } else {
                this.fetching = false;
            }
        }
    }
</script>
