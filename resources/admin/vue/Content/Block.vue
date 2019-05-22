<template>
    <div>
        <h3><i class="fal fa-fw fa-edit"></i> Blokken</h3>

        <hr />

        <select class="custom-select mb-3" v-model="selected" v-on:change="getBlock">
            <option value="0">--- Selecteer een blok ---</option>
            <option v-for="block in blocks" :value="block.id">{{ block.title }}</option>
        </select>

        <textarea id="block-editor"></textarea>

        <button class="btn btn-outline-success mt-3" v-if="selected != 0" v-on:click="saveBlock">Opslaan</button>
    </div>
</template>

<script>
    export default {
        props: ['url', 'blocks'],
        data () {
            return {
                editor: null,
                sku: '',
                selected: 0
            }
        },
        methods: {
            getBlock () {
                this.$data.editor.setData('');

                if (this.$data.selected == 0) {
                    return;
                }

                axios.get(this.url + '/' + this.$data.selected)
                    .then((response) => {
                        if (response.data.payload) {
                            this.$data.editor.setData(response.data.payload.content);
                        }
                    })
                    .catch((error) => {
                        this.$root.$emit('send-notify', {
                            success: false,
                            text: error.response.data.message
                        });
                    });
            },
            saveBlock () {
                axios.put(this.url, {
                    block: this.$data.selected,
                    data: this.$data.editor.getData()
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
            this.$data.editor = CKEDITOR.replace('block-editor');
        }
    }
</script>