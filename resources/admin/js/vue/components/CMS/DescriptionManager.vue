<template>
    <div id="description-manager">
        <h3>
            <i class="fal fa-fw fa-align-center"></i>
            Product omschrijvingen
        </h3>

        <hr />

        <input class="form-control mb-3" maxlength="8" v-model="sku"
               @input="getDescription" placeholder="Productnummer" />

        <textarea id="description-editor"></textarea>

        <button class="btn btn-outline-success mt-3" @click="saveDescription">Opslaan</button>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                editor: null,
                sku: ''
            }
        },
        methods: {
            getDescription () {
                this.editor.setData('');

                if (this.sku.length !== 8) {
                    return;
                }

                this.$http.get(route('admin.api.cms-find-description'), {
                    params: {
                        sku: this.sku
                    }
                })
                    .then(this._handleNotifyResponse)
                    .then((response) => {
                        if (response.data.description) {
                            this.editor.setData(response.data.description.value);
                        }
                    })
                    .catch(this._handleAxiosError);
            },
            saveDescription () {
                this.$http.put(this.url, {
                    sku: this.sku,
                    description: this.editor.getData()
                })
                    .then(this._handleNotifyResponse)
                    .catch(this._handleAxiosError);
            },
            _handleNotifyResponse(response) {
                if (response.data.message) {
                    this.$root.$emit('send-notify', {
                        text: response.data.message,
                        success: response.data.success
                    });
                }

                return response;
            },
            _handleAxiosError(error) {
                console.error(error);

                this.$root.$emit('send-notify', {
                    text: error,
                    success: false
                });
            }
        },
        mounted () {
            this.editor = CKEDITOR.replace('description-editor');
        }
    }
</script>