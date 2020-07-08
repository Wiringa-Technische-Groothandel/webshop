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
            Prijs tijdelijk niet beschikbaar
        </div>

        <template v-if="! error">
            <template v-if="isAction">
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
            <small class="form-text text-muted price-per" v-if="priceUnit">
                {{ pricePerStr }}
            </small>

            <small class="form-text text-muted stock" v-if="stock !== false">
                {{ stockStr }}
            </small>
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
                isAction: false,
                priceFactor: false,
                priceUnit: false,
                scaleUnit: false,
                stock: false,
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
            stockStr () {
                switch (this.product.stock_display) {
                    case 'S':
                        if (this.stock === false) {
                            return '';
                        } else if (this.stock === 0) {
                            return 'In bestelling, bel voor meer info';
                        } else {
                            return 'Voorraad: %1 %2'
                                .replace('%1', this.stock)
                                .replace('%2', this.stock > 1 ? this.product.sales_unit_long_plural : this.product.sales_unit_long);
                        }
                    case 'A':
                        return 'Levertijd in overleg';
                    case 'V':
                        return  'Binnen 24/48 uur mits voor 16.00 besteld';
                    default:
                        return '';
                }
            },
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
                }, 5000); // 5 second timeout

                this.$root.$on('price-fetched-' + this.product.sku, data => {
                    clearTimeout(timeout);

                    this.fetching = false;

                    const price = data.price;
                    const stock = data.stock;

                    if (! price && ! stock) {
                        this.error = true;

                        return;
                    }

                    if (price) {
                        this.netPrice = price.netPrice;
                        this.grossPrice = price.grossPrice;
                        this.pricePer = price.pricePer;
                        this.isAction = price.actionPrice;
                        this.priceFactor = price.priceFactor;
                        this.priceUnit = price.priceUnit;
                        this.scaleUnit = price.scaleUnit;
                    }

                    if (stock) {
                        this.stock = stock.displayStock;
                    }
                });
            } else {
                this.fetching = false;
            }
        }
    }
</script>
