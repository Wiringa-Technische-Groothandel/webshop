<template>
    <div class="card" id="contact-email">
        <div class="card-header">
            Contact e-mail
        </div>
        <div class="card-body">
            <p class="card-text">
                Naar dit e-mail adres worden contact gerelateerde mails gestuurd, bijvoorbeeld een wachtwoord reset link.
            </p>

            <div class="alert alert-warning" v-if="!newEmail">
                <i class="fal fa-fw fa-exclamation-triangle"></i>
                <b>Let op:</b> Zonder contact e-mail kunt u uw wachtwoord niet resetten als u deze bent vergeten.
            </div>

            <input class="form-control" title="Contact email" type="email" v-model="newEmail" />

            <button type="submit" class="btn btn-success my-2" @click="submit">
                Opslaan
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['email', 'submitUrl'],
        methods: {
            submit () {
                if (! this.$data.newEmail) {
                    this.$root.$emit('send-notify', {
                        success: false,
                        text: 'Geen geldig e-mail adres opgegeven.'
                    });

                    return;
                }

                axios.put(this.submitUrl, {
                        email: this.$data.newEmail
                    })
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            success: response.data.success,
                            text: response.data.message
                        });
                    })
                    .catch((error) => {
                        console.log(error);

                        this.$root.$emit('send-notify', {
                            success: false,
                            text: error.response.data.errors
                        });
                    });
            }
        },
        data () {
            return {
                showSaveButton: false,
                newEmail: ''
            }
        },
        created () {
            this.$data.newEmail = this.email;
        }
    }
</script>