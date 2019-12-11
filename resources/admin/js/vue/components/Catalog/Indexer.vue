<template>
    <div id="product-indexer" class="card mb-3">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-sync"></i> Opnieuw indexeren
            </h3>

            <hr>

            <div class="alert alert-warning">
                <i class="fas fa-fw fa-exclamation-triangle"></i>
                Tijdens het indexeren zal de zoekfunctie tijdelijk niet werken.
            </div>

            <button class="btn btn-raised btn-success" @click="submit" :disabled="indexing">
                <span v-if="!indexing">Indexatie starten</span>
                <template v-else>
                    <span><i class="fas fa-circle-notch fa-spin"></i> Indexeren...</span>
                </template>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                indexing: false
            }
        },
        methods: {
            async submit () {
                this.indexing = true;

                await this.$http.post(route('admin.api.reindex-products'))
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });
                    })
                    .catch((error) => {
                        console.error(error);

                        this.$root.$emit('send-notify', {
                            text: error,
                            success: false
                        });
                    });

                this.indexing = false;
            }
        }
    }
</script>