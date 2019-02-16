<template>
    <div class="card mb-3">
        <div class="card-body">
            <dl>
                <dt>Gebruikersnaam</dt>
                <dd>{{ account.username }}</dd>

                <dt>E-Mail</dt>
                <dd>{{ account.contact.contactEmail }}</dd>

                <dt>Rol</dt>
                <dd>
                    <div class="fa fa-spinner fa-spin" v-if="showSpinner"></div>

                    <select class="form-control" v-model="role" :disabled="Boolean(canEdit)" @change="updateRole">
                        <option :value="roleAdmin">Admin</option>
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
            'canEdit', 'account', 'canAssignAdmin', 'canAssignManager',
            'roleUser', 'roleManager', 'roleAdmin', 'currentRole'
        ],
        data () {
            return {
                role: this.currentRole,
                showSpinner: false
            }
        },
        methods: {
            updateRole () {
                console.log('update ' + this.account.id)
            }
        },
        mounted() {
            console.log(this.account)
        }
    }
</script>