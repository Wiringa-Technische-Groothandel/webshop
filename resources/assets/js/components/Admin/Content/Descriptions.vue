<template>
    <div>
        <h3><i class="fal fa-fw fa-align-center"></i> Product omschrijvingen</h3>

        <hr />

        <input class="form-control mb-3" maxlength="8" v-model="sku" v-on:input="getDescription" placeholder="Productnummer" />

        <textarea id="description-editor"></textarea>

        <button class="btn btn-outline-success mt-3" v-on:click="saveDescription">Opslaan</button>
    </div>
</template>

<script>
    export default {
        props: ['url'],
        data () {
            return {
                editor: null,
                sku: ''
            }
        },
        methods: {
            getDescription () {
                this.$data.editor.setData('');

                if (this.$data.sku.length !== 8) {
                    return;
                }

                axios.get(this.url + '/' + this.$data.sku)
                    .then((response) => {
                        if (response.data.payload) {
                            this.$data.editor.setData(response.data.payload.value);
                        }
                    })
                    .catch((error) => {
                        this.$root.$emit('send-notify', {
                            success: false,
                            text: error.response.data.message
                        });
                    });
            },
            saveDescription () {
                axios.put(this.url, {
                    sku: this.$data.sku,
                    description: this.$data.editor.getData()
                })
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            success: response.data.success,
                            text: response.data.message
                        });
                    })
                    .catch((error) => {
                        this.$root.$emit('send-notify', {
                            success: false,
                            text: error.response.data.message
                        });
                    });
            }
        },
        mounted () {
            this.$data.editor = CKEDITOR.replace('description-editor');
        }
    }
</script>