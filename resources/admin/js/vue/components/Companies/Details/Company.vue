<template>
    <div class="card" id="company-details">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-building"></i> Gegevens

                <template v-if="!company.deleted_at">
                    <button class="btn btn-danger float-right" @click="confirmDeletion">
                        <i class="fal fa-fw fa-trash-alt"></i> Verwijderen
                    </button>

                    <button class="btn btn-primary float-right" @click="dirty = !dirty">
                        <i class="fal fa-fw fa-pencil"></i> Aanpassen
                    </button>
                </template>
            </h3>

            <hr />

            <div class="alert alert-warning" v-if="password">
                Het wachtwoord voor de nieuwe gebruiker is: <b>{{ password }}</b>
                <br><br>
                <b>Let op:</b> Het wachtwoord is alleen nu zichtbaar!
            </div>

            <div class="alert alert-danger" v-if="company.deleted_at">
                Dit bedrijf is gemarkeerd als verwijderd, op {{ getDeletionMoment(company.deleted_at) }}
                wordt deze definitief verwijderd.
                <br><br>
                <button class="btn btn-outline-warning" @click="cancelDeletion">
                    Verwijdering annuleren
                </button>
            </div>

            <dl class="row">
                <dt class="col-sm-4">Naam</dt>
                <dd class="col-sm-8">
                    <input v-model="company.name" class="form-control" :disabled="!dirty">
                </dd>

                <dt class="col-sm-4">Debiteurnummer</dt>
                <dd class="col-sm-8">
                    <input v-model="company.customer_number" class="form-control" :disabled="!dirty">
                </dd>

                <dt class="col-sm-4">Straat</dt>
                <dd class="col-sm-8">
                    <input v-model="company.street" class="form-control" :disabled="!dirty">
                </dd>

                <dt class="col-sm-4">Plaats</dt>
                <dd class="col-sm-8">
                    <input v-model="company.city" class="form-control" :disabled="!dirty">
                </dd>

                <dt class="col-sm-4">Postcode</dt>
                <dd class="col-sm-8">
                    <input v-model="company.postcode" class="form-control" :disabled="!dirty">
                </dd>

                <dt class="col-sm-4">Is actief</dt>
                <dd class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is-active"
                               v-model="company.active" :disabled="!dirty">
                        <label class="custom-control-label" for="is-active"></label>
                    </div>
                </dd>
            </dl>

            <button class="btn btn-success float-right" @click="fireSave" v-if="dirty">Opslaan</button>
        </div>
    </div>
</template>

<script>
    import { format, addHours, parseISO } from 'date-fns'
    import nl from 'date-fns/locale/nl'

    export default {
        props: {
            company: {
                required: true,
                type: Object
            },
            password: {
                require: false,
                type: String,
                default () {
                    return '';
                }
            }
        },
        data () {
            return {
                dirty: false
            }
        },
        methods: {
            fireChange () {
                this.$emit('change', this.company);
            },
            fireSave () {
                this.$emit('save', this.company);
                this.dirty = false;
            },
            generateControlNumber() {
                return ((Math.random() * 10000) + 1000).toFixed(0);
            },
            getDeletionMoment() {
                const date = parseISO(this.company.deleted_at);
                const deletionDate = addHours(date, 6);

                return format(deletionDate, 'PPPPp', { locale: nl })
            },
            cancelDeletion () {
                this.$http.post(route('admin.api.cancel-company-deletion'), {
                    id: this.company.id
                })
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });

                        this.fireChange();
                    })
                    .catch(this.handleError);
            },
            confirmDeletion () {
                const cont = confirm(
                    'Let op! U staat op het punt om debiteur ' +
                    this.company.customer_number +
                    ' met de bijbehorende accounts volledig uit het systeem' +
                    ' te verwijderen, dit kan NIET ongedaan gemaakt worden. Wilt u doorgaan?'
                );

                let absoluteConfirmation = null;
                const controlNumber = this.generateControlNumber();

                if (cont) {
                    const controlInput = prompt('Vul het controlenummer in: ' + controlNumber);

                    if (controlInput === null) {
                        return;
                    }

                    absoluteConfirmation = controlInput === controlNumber;
                } else {
                    return;
                }

                if (!absoluteConfirmation) {
                    this.$root.$emit('send-notify', {
                        text: 'De controle nummers komen niet overeen.',
                        success: false
                    });

                    return;
                }

                this.deleteCompany();
            },
            deleteCompany () {
                this.$http.delete(route('admin.api.delete-company'), {
                    params: {
                        id: this.company.id
                    }
                })
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });

                        if (response.data.success) {
                            this.$emit('change', this.company);
                        }
                    })
                    .catch(this.handleError);
            },
            handleError (error) {
                console.error(error);

                this.$root.$emit('send-notify', {
                    text: error,
                    success: false
                });
            }
        }
    }
</script>