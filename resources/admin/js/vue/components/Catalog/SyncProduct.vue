<template>
    <div class="card mb-3" id="sync-product">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-sync"></i> Update product
            </h3>

            <hr>

            <div class="form-group">
                <label for="product-sync-input" class="control-label">Productnummer</label>
                <input id="product-sync-input" v-model="skuInput" class="form-control" type="number" :disabled="loading" />
            </div>

            <button class="btn btn-raised btn-success" @click="syncProduct" :disabled="loading">
                <span v-if="!loading">Update</span>
                <template v-else>
                    <span><i class="fas fa-circle-notch fa-spin"></i> Updaten...</span>
                </template>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            sku: {
                required: false,
                type: [String, Number],
                default () {
                    return '';
                }
            }
        },
        watch: {
            sku (val) {
                this.skuInput = val;
            }
        },
        data () {
            return {
                skuInput: '',
                loading: false
            }
        },
        methods: {
            async syncProduct () {
                if (this.skuInput.length < 7) {
                    return;
                }

                this.loading = true;

                await this.$http.post(route('admin.api.sync-product'), {
                    sku: this.skuInput
                }).then((response) => {
                    if (response.data.success) {
                        this.$emit('synced', this.skuInput);
                    }

                    this.$root.$emit('send-notify', {
                        text: response.data.message,
                        success: response.data.success
                    });
                }).catch((e) => {
                    console.error(e);
                });

                this.loading = false;
            }
        }
    }
</script>
