<template>
    <div class="input-group add-to-cart">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ quantity > 1 ? salesUnitPlural : salesUnitSingle }}</span>
        </div>
        <input type="number" step="1" :min="initialQuantity" class="form-control" placeholder="Aantal" v-model="quantity">
        <div class="input-group-append">
            <button class="btn btn-outline-success" v-on:click="this.addToCart">
                <span><i class="fas fa-fw fa-cart-plus"></i></span>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            sku: {
                required: true,
                type: String
            },
            salesUnitSingle: {
                required: true,
                type: String
            },
            salesUnitPlural: {
                required: true,
                type: String
            },
            submitUrl: {
                required: true,
                type: String
            },
            initialQuantity: {
                required: false,
                type: Number,
                default () {
                    return 1;
                }
            }
        },
        data () {
            return {
                quantity: this.initialQuantity
            };
        },
        methods: {
            addToCart () {
                axios.put(this.submitUrl, {
                    quantity: this.$data.quantity,
                    product: this.sku
                })
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });

                        if (response.data.success) {
                            this.$root.$emit('update-cart-count', response.data.count);
                        }
                    })
                    .catch((error) => {
                        console.log(error);

                        this.$root.$emit('send-notify', {
                            text: "Er is een probleem opgetreden tijdens het toevoegen van het product.",
                            success: false
                        });
                    });
            }
        }
    }
</script>
