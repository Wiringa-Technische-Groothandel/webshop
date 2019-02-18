<template>
    <div class="card mb-3">
        <div class="card-body">
            <dl>
                <dt>Gebruikersnaam</dt>
                <dd>{{ account.username }}</dd>

                <dt>E-Mail</dt>
                <dd>{{ account.contact.contactEmail }}</dd>

                <dt>Rol  <i class="fa fa-spinner fa-spin" v-if="showSpinner"></i></dt>
                <dd>
                    <select class="form-control" v-model="role" :readonly="showSpinner"
                            :disabled="Boolean(canEdit)" @change="updateRole">
                        <option :value="roleManager">Manager</option>
                        <option :value="roleUser">Gebruiker</option>
                    </select>
                </dd>
            </dl>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        props: [
            'canEdit', 'account', 'roleUser', 'roleManager', 'roleAdmin', 'currentRole', 'updateRoleUrl'
        ],
        data () {
            return {
                role: this.currentRole,
                showSpinner: false
            }
        },
        methods: {
            updateRole () {
                if (this.$data.showSpinner) {
                    return;
                }

                this.$data.showSpinner = true;

                axios.post(this.updateRoleUrl, {
                        account: this.account.id,
                        role: this.$data.role
                    })
                    .then((resp) => {
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
            }
        }
    }
</script>