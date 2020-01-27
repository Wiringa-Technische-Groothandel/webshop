<template>
    <div class="card mb-3" v-if="!deleted">
        <div class="card-body">
            <span @click="deleteAccount" class="delete-button" v-if="canEdit">&times;</span>

            <dl>
                <dt>Gebruikersnaam</dt>
                <dd>{{ account.username }}</dd>

                <dt>E-Mail</dt>
                <dd>{{ account.contact.contact_email }}</dd>

                <dt>
                    Rol

                    <template v-if="showSpinner">
                        <span><i class="fa fa-spinner fa-spin"></i></span>
                    </template>
                </dt>
                <dd>
                    <select class="custom-select" v-model="role" :readonly="showSpinner"
                            :disabled="!canEdit" @change="updateRole">
                        <option :value="roleManager">Manager</option>
                        <option :value="roleUser">Gebruiker</option>
                    </select>
                </dd>
            </dl>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .card-body {
        position: relative;

        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            padding: 5px;
            font-size: 20px;
            color: #333;

            &:hover {
                cursor: pointer;
            }
        }
    }
</style>

<script>
    import axios from 'axios'

    export default {
        props: {
            'canEdit': {
                required: true,
                type: Boolean
            },
            'account': {
                required: true,
                type: Object
            },
            'roleUser': {
                required: true,
                type: Number
            },
            'roleManager': {
                required: true,
                type: Number
            },
            'currentRole': {
                required: true,
                type: Number
            },
            'updateRoleUrl': {
                required: true,
                type: String
            },
            'deleteAccountUrl': {
                required: true,
                type: String
            }
        },
        data () {
            return {
                deleted: false,
                role: this.currentRole,
                showSpinner: false
            }
        },
        methods: {
            handleResponse (r) {
                return r.then((resp) => {
                    this.$root.$emit('send-notify', {
                        text: resp.data.message,
                        success: true
                    });
                })
                .catch((error) => {
                    this.$root.$emit('send-notify', {
                        success: false,
                        text: error
                    });
                })
                .then(() => {
                    this.$data.showSpinner = false;
                })
            },
            deleteAccount () {
                if (this.$data.showSpinner) {
                    return;
                }

                if (! confirm('Weet u zeker dat u dit account wilt verwijderen?')) {
                    return;
                }

                this.$data.showSpinner = true;

                const resp = axios.delete(this.deleteAccountUrl).then(resp => {
                    this.$data.deleted = resp.status === 200;

                    return resp;
                });

                this.handleResponse(resp);
            },
            updateRole () {
                if (this.$data.showSpinner) {
                    return;
                }

                this.$data.showSpinner = true;

                const resp = axios.post(this.updateRoleUrl, {
                        account: this.account.id,
                        role: this.$data.role
                    });

                this.handleResponse(resp);
            }
        }
    }
</script>