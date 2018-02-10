<template>
    <div class="col-12">
        <div class="row cart-item">
            <div class="col-10 col-sm-5 col-lg-6">
                <div class="cart-item-name">
                    <a class="product-name" :href="'/product/' + item.product.sku">
                        {{ item.product.name }}
                    </a>
                </div>
            </div>

            <div class="col-2 col-sm-1 order-sm-2">
                <div class="cart-item-delete text-right">
                    <button class="btn btn-link" v-on:click="this.delete">
                        <i class="fal fa-fw fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <div class="col-4 col-sm-2 order-sm-1 d-none d-md-block d-lg-block d-xl-block">
                <div class="cart-item-price text-left">
                    <i class="far fa-fw fa-euro-sign"></i> {{ item.price }}
                </div>
            </div>

            <div class="col-4 col-md-2 col-lg-1 col-sm-3 order-sm-1">
                <div class="cart-item-qty text-right">
                    <input type="number" class="form-control" placeholder="Aantal" min="1" step="1"
                           v-model="quantity" v-on:change="this.update" />
                </div>
            </div>

            <div class="col-4 col-sm-2 order-sm-1">
                <div class="cart-item-subtotal text-right">
                    <i class="far fa-fw fa-euro-sign"></i> {{ item.subtotal }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['item'],
        methods: {
            delete () {
                this.$root.$emit('show-cart-overlay');

                axios.delete('/checkout/cart/product/' + this.item.product.sku)
                    .then((response) => {
                        if (response.data.success) {
                            this.$root.$emit('fetch-cart-items');

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
            update () {
                this.$root.$emit('show-cart-overlay');

                axios.patch('/checkout/cart', {
                    sku: this.item.product.sku,
                    quantity: this.$data.quantity
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
            }
        },
        data () {
            return {
                quantity: this.item.qty
            };
        },
        mounted () {
            console.log('Cart item component mounted');
        }
    }
</script>