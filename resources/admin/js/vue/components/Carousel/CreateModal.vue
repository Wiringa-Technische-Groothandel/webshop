<template>
    <div class="modal fade" tabindex="-1" id="create-slide-modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Slide toevoegen</h5>
                    <button type="button" class="close" @click="$emit('close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="imageFile">Afbeelding</label>

                        <div class="custom-file">
                            <input @change="fileSelected" type="file" class="custom-file-input" ref="image"
                                   id="imageFile" accept="image/*">
                            <label class="custom-file-label" for="imageFile"
                                   v-html="this.image ? this.image.name : 'Kies een bestand'"></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Titel</label>
                        <input v-model="title" type="text" class="form-control" maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label>Omschrijving</label>
                        <input v-model="caption" class="form-control" type="text" maxlength="200" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-raised btn-primary mr-2" @click="submit">Toevoegen</button>
                    <button class="btn btn-danger" @click="$emit('close')">Sluiten</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .custom-control-label {
        line-height: 1.5;
    }
</style>

<script>
    export default {
        props: {
            show: {
                required: false,
                type: Boolean,
                default() {
                    return false;
                }
            }
        },
        watch: {
            show(show) {
                if (show) {
                    this.modal.modal('show');
                } else {
                    this.modal.modal('hide');
                }
            }
        },
        data() {
            return {
                modal: null,
                image: null,
                title: '',
                caption: ''
            }
        },
        methods: {
            fileSelected() {
                if (this.$refs.image.files[0]) {
                    this.image = this.$refs.image.files[0];
                } else {
                    this.image = null;
                }
            },
            resetForm() {
                this.image = null;
                this.title = '';
                this.caption = '';
            },
            submit() {
                const formData = new FormData();
                formData.append('title', this.title);
                formData.append('caption', this.caption);
                formData.append('image', this.image);

                this.$http.post(route('admin.api.create-slide'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then((response) => {
                        if (response.data.success) {
                            this.resetForm();

                            this.$emit('close');
                            this.$emit('slide-created');
                        }

                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });
                    })
                    .catch((error) => {
                        this.$root.$emit('send-notify', {
                            text: error,
                            success: false
                        });
                    });
            }
        },
        mounted() {
            this.modal = $('#create-slide-modal');

            this.modal.on('hidden.bs.modal', () => {
                this.$emit('hidden');
            });

            this.modal.on('hide.bs.modal', () => {
                this.$emit('hide');
            });

            this.modal.on('shown.bs.modal', () => {
                this.$emit('shown');
            });

            this.modal.on('show.bs.modal', () => {
                this.$emit('show');
            });
        }
    }
</script>