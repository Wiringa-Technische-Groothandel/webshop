<template>
    <div class="col-12">
        <div class="row cart-item">
            <div class="col-11">
                <div class="row">
                    <div class="col-md-6 col-lg-8">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="cart-item-name">
                                    <a class="product-name" :href="'/product/' + item.product.sku">
                                        {{ item.product.name }}
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="cart-item-qty">
                                    <input type="number" class="form-control" placeholder="Aantal"
                                           :min="item.product.minimal_purchase" :step="item.product.minimal_purchase"
                                           v-model="quantity" v-on:input="this.update" :id="'cart-item-qty-' + item.product.sku" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="row">
                            <div class="col text-left">
                                <div class="cart-item-price">
                                    <label class="d-block d-md-none">Prijs</label>
                                    <i class="far fa-euro-sign"></i> {{ item.price.toFixed(2).replace(".", ",") }} per {{ item.product.sales_unit_long }}
                                </div>
                            </div>

                            <div class="col text-right">
                                <div class="cart-item-subtotal">
                                    <label class="d-block d-md-none">Subtotaal</label>
                                    <i class="far fa-euro-sign"></i> {{ item.subtotal.toFixed(2).replace(".", ",") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-1 px-0">
                <div class="cart-item-delete text-right">
                    <button class="btn btn-link px-0 px-sm-1" v-on:click="this.delete">
                        <i class="fal fa-fw fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .cart-item {
        font-size: 15px;
        line-height: 2.6;
        border: 1px solid #efefef;
        border-left: none;
        border-right: none;
        padding: 8px 0;

        .cart-item-price,
        .cart-item-subtotal {
            @media only screen and (max-width: 767px) {
                line-height: initial;
            }

            white-space: nowrap;

            label {
                margin-top: 10px;
                font-weight: bold;
            }
        }

        .cart-item-name {
            a {
                font-weight: bold;
                color: #333;
            }
        }

        .cart-item-delete {
            .btn-link {
                font-size: 1.2rem;
                color: var(--danger);
            }
        }
    }
</style>

<script>
    export default {
        props: ['item'],
        methods: {
            resetQuantity() {
                $('#cart-item-qty-' + this.item.product.sku).val(Math.round(this.item.qty));

                this.quantity = Math.round(this.item.qty);
            },
            delete () {
                this.$root.$emit('show-cart-overlay');

                axios.delete('/checkout/cart/product/' + this.item.product.sku)
                    .then((response) => {
                        if (response.data.success) {
                            this.$root.$emit('fetch-cart-items');
                            this.$root.$emit('update-cart-count', response.data.count);
                            this.$root.$emit('send-notify', {
                                text: response.data.message,
                                success: true
                            });
                        }
                    })
                    .catch((error) => {
                        this.$root.$emit('hide-cart-overlay');

                        console.log(error);
                    });
            },
            update (event) {
                let currentQty = this.quantity;

                setTimeout(() => {
                    if (currentQty !== this.quantity) {
                        return;
                    }
                    
                    if (this.quantity < this.item.product.minimal_purchase && this.quantity > 0) {
                        this.$root.$emit('send-notify', {
                            error: true,
                            text: `Het product "${this.item.product.name}" heeft een minimale besteleenheid van ${this.item.product.minimal_purchase}`
                        });
                        this.resetQuantity();
                        return;
                    }

                    if ((this.quantity % this.item.product.minimal_purchase) !== 0) {
                        this.$root.$emit('send-notify', {
                            error: true,
                            text: `Het product "${this.item.product.name}" kan alleen in veelvouden van ${this.item.product.minimal_purchase} besteld worden`
                        });
                        this.resetQuantity();
                        return;
                    }

                    event.target.blur();

                    this.$root.$emit('show-cart-overlay');

                    axios.patch('/checkout/cart', {
                        sku: this.item.product.sku,
                        quantity: this.quantity
                    })
                        .then((response) => {
                            if (response.data.success) {
                                this.$root.$emit('fetch-cart-items');
                            }
                        })
                        .catch((error) => {
                            this.$root.$emit('hide-cart-overlay');

                            console.log(error);
                        });
                }, 1000); // Wait 1 second for more input
            }
        },
        data () {
            return {
                quantity: Math.round(this.item.qty)
            };
        },
        mounted() {
            $('#cart-item-qty-' + this.item.product.sku).inputSpinner();
        }
    }
</script>
