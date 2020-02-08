<template>
    <div class="modal fade" tabindex="-1" id="create-company-modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Debiteur toevoegen</h5>
                    <button type="button" class="close" @click="$emit('close')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Naam</label>
                        <input v-model="name" class="form-control" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Debiteurnummer</label>
                        <input v-model="customer_number" type="text" class="form-control" maxlength="5" required>
                    </div>

                    <div class="form-group">
                        <label>Street</label>
                        <input v-model="street" class="form-control" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Postcode</label>
                        <input v-model="postcode" maxlength="7" class="form-control" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Plaats</label>
                        <input v-model="city" class="form-control" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>E-Mail</label>
                        <input v-model="email" class="form-control" type="text" required>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is-active" v-model="active">
                        <label class="custom-control-label" for="is-active">Is actief</label>
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
                name: '',
                customer_number: '',
                street: '',
                postcode: '',
                city: '',
                email: '',
                active: true
            }
        },
        methods: {
            resetForm () {
                this.name = '';
                this.customer_number = '';
                this.street = '';
                this.postcode = '';
                this.city = '';
                this.email = '';
                this.active = true;
            },
            submit () {
                this.$http.post(route('admin.api.create-company'), {
                    name: this.name,
                    customer_number: this.customer_number,
                    street: this.street,
                    postcode: this.postcode,
                    city: this.city,
                    email: this.email,
                    active: this.active
                })
                    .then((response) => {
                        if (response.data.success) {
                            this.resetForm();

                            this.$emit('close');

                            this.$nextTick(() => {
                                this.$emit('company-created', response.data.companyId);
                            });
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
            this.modal = $('#create-company-modal');

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