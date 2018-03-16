<template>
    <div id="address-list">
        <div class="row" v-if="addresses.length > 0">
            <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6" v-for="address in addresses">
                <div class="card mb-3">
                    <div class="card-body">
                        <address>
                            <b>{{ address.name }}</b><br />
                            {{ address.street }} <br />
                            {{ address.postcode }} {{ address.city }} <br />
                            <i class="fal fa-fw fa-phone"></i> {{ address.phone || '-' }} <br />
                            <i class="fal fa-fw fa-mobile"></i> {{ address.mobile || '-' }}
                        </address>

                        <button @click="setDefault(address.id)" v-if="address.id !== defaultAddressId"
                                class="btn btn-secondary">
                            Maak standaard adres
                        </button>

                        <button type="submit" class="btn btn-disabled" disabled v-else>
                            Standaard adres
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning mx-auto" v-else>
            <i class="fal fa-fw fa-exclamation-triangle"></i> <b>Let op:</b>
            U hebt nog geen addressen gekoppeld aan uw account. Zonder adressen kunt u geen bestellingen plaatsen.
        </div>
    </div>
</template>

<script>
    export default {
        props: ['addresses', 'defaultAddress', 'updateUrl'],
        methods: {
            setDefault (addressId) {
                axios.patch(this.updateUrl, {
                    address: addressId
                })
                    .then((response) => {
                        this.$data.defaultAddressId = addressId;

                        this.$root.$emit('send-notify', {
                            success: response.data.success,
                            text: response.data.message
                        });
                    })
                    .catch((error) => {
                        console.log(error);

                        if (error.response) {
                            this.$root.$emit('send-notify', {
                                success: false,
                                text: error.response.data.message
                            });
                        }
                    });
            }
        },
        data () {
            return {
                defaultAddressId: (this.defaultAddress ? parseInt(this.defaultAddress) : null)
            }
        }
    }
</script>