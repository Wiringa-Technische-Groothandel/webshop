<template>
    <div id="block-manager">
        <h3><i class="fal fa-fw fa-edit"></i> Blokken</h3>

        <hr/>

        <select class="custom-select mb-3" v-model="selected" :disabled="blocks.length < 1">
            <option :value="-1">{{ firstOptionText }}</option>
            <option v-for="(block, index) in blocks" :value="index">{{ block.title }}</option>
        </select>

        <textarea id="block-editor"></textarea>

        <button class="btn btn-outline-success mt-3" v-if="selected !== -1" @click="saveBlock">Opslaan</button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                firstOptionText: '--- Laden... ---',
                blocks: [],
                editor: null,
                selected: -1
            }
        },
        watch: {
            selected (val) {
                if (val === -1) {
                    this.editor.setData('');
                } else {
                    if (this.blocks[val]) {
                        this.editor.setData(this.blocks[val].content);
                    }
                }
            }
        },
        methods: {
            fetchBlocks() {
                this.$http.get(route('admin.api.cms-get-blocks'))
                    .then((response) => {
                        if (response.data.blocks) {
                            this.firstOptionText = '--- Selecteer een blok ---';
                            this.blocks = response.data.blocks;
                        } else {
                            this.firstOptionText = response.data.message;
                        }
                    })
                    .catch(this._handleAxiosError);
            },
            saveBlock() {
                this.$http.post(route('admin.api.cms-save-block'), {
                    block: this.blocks[this.selected].id,
                    content: this.editor.getData()
                })
                    .then((response) => {
                        if (response.data.message) {
                            this.$root.$emit('send-notify', {
                                text: response.data.message,
                                success: response.data.success
                            });
                        }

                        this.fetchBlocks();
                    })
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
        mounted() {
            this.fetchBlocks();
            this.editor = CKEDITOR.replace('block-editor');
        }
    }
</script>