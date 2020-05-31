<template>
    <div class="input-group add-to-cart">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ quantity > 1 ? salesUnitPlural : salesUnitSingle }}</span>
        </div>
        <input ref="input" type="number" :step="step" :min="step"
               class="form-control" placeholder="Aantal" v-model="quantity">
        <div class="input-group-append">
            <button class="btn btn-outline-success" @click="this.addToCart">
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
            step: {
                required: false,
                type: Number,
                default () {
                    return 1;
                }
            }
        },
        data () {
            return {
                quantity: this.step
            };
        },
        methods: {
            addToCart () {
                if (typeof this.$refs.input.reportValidity !== "function") {
                    if (this.quantity < this.step) {
                        this.quantity = this.step;
                    } else if (this.quantity % this.step !== 0) {
                        this.quantity = Math.ceil(this.quantity / this.step) * this.step;
                    }
                } else {
                    if (! this.$refs.input.reportValidity()) {
                        return;
                    }
                }

                axios.put(this.submitUrl, {
                    quantity: this.quantity,
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
