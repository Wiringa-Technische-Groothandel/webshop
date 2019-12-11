<template>
    <div class="row align-items-center" style="height: 100vh;">
        <div class="col-12 col-md-4 mx-auto">
            <img class="img-fluid mb-3" src="/storage/static/images/logov2.png" alt="WTG Logo">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center font-weight-light">Admin login</h3>

                    <hr />

                    <form @submit.prevent="login">
                        <div class="alert alert-danger" v-if="loginError">
                            <i class="fal fa-fw fa-exclamation-triangle"></i> {{ loginError }}
                        </div>

                        <div class="form-group">
                            <label>Gebruikersnaam</label>
                            <input type="text" class="form-control" v-model="username"
                                   placeholder="Gebruikersnaam" tabindex="1" autofocus>
                        </div>

                        <div class="form-group">
                            <label>Wachtwoord</label>
                            <input type="password" class="form-control" v-model="password"
                                   placeholder="Wachtwoord" tabindex="2">
                        </div>

                        <button type="submit" class="btn btn-raised btn-success float-right">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                loginError: false,
                username: '',
                password: ''
            }
        },
        methods: {
            login () {
                this.loginError = false;

                this.$auth.login(this.username, this.password)
                    .then((data) => {
                        if (data.success) {
                            this.$store.commit('auth/login', {
                                token: data.token,
                                expires_at: data.expires_at
                            });
                        } else {
                            this.loginError = data.message;
                        }
                    })
                    .catch((error) => {
                        console.error(error);

                        this.$root.$emit('send-notify', {
                            text: error,
                            success: false
                        });
                    });
            },
            checkToken () {
                if (this.$store.getters['auth/hasToken'] && this.$store.getters['auth/isExpired']) {
                    this.$store.commit('auth/refresh')
                }
            }
        },
        mounted() {
            this.checkToken();
        }
    }
</script>