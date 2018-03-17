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
    let editor;

    export default {
        props: ['url'],
        data () {
            return {
                sku: ''
            }
        },
        methods: {
            getDescription () {
                editor.setData('');

                if (this.$data.sku.length !== 8) {
                    return;
                }

                axios.post(this.url, {
                    sku: this.$data.sku
                })
                    .then((response) => {
                        console.log(response.data.payload);

                        if (response.data.payload) {
                            editor.setData(response.data.payload.value);
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
                    description: editor.getData()
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
            editor = CKEDITOR.replace('description-editor');
        }
    }
</script>