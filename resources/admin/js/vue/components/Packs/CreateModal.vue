<template>
    <div class="modal fade" tabindex="-1" id="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actiepaket toevoegen</h5>
                    <button type="button" class="close" @click="$emit('close')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Artikelnummer</label>
                        <input v-model="sku" class="form-control" type="text" required>
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

<script>
    export default {
        props: {
            show: {
                required: false,
                type: Boolean,
                default () {
                    return false;
                }
            }
        },
        watch: {
            show (show) {
                if (show) {
                    this.modal.modal('show');
                } else {
                    this.modal.modal('hide');
                }
            }
        },
        data () {
            return {
                modal: null,
                sku: ''
            }
        },
        methods: {
            resetForm () {
                this.sku = '';
            },
            submit () {
                this.$http.post(route('admin.api.create-pack'), {
                    sku: this.sku
                })
                    .then((response) => {
                        if (response.data.success) {
                            this.resetForm();

                            this.$emit('close');
                            this.$emit('pack-created');
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
            this.modal = $('#modal');

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