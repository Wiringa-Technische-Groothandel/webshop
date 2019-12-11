<template>
    <div id="cart-address">
        <address id="delivery-address" v-if="address">
            <b>{{ address.name }}</b><br />
            {{ address.street }} <br />
            {{ address.postcode }} {{ address.city }}
        </address>

        <div class="alert alert-warning" v-else>
            <i class="fal fa-fw fa-exclamation-triangle"></i> <b>Let op:</b>
            U heeft nog geen afleveradres geselecteerd. Selecteer een adres om de bestelling af te kunnen ronden.
        </div>
    </div>
</template>

<script>
    export default {
        props: ['address'],
        mounted () {
            this.$root.$on('cart-address-changed', (address) => {
                this.address = address;
            });
        }
    }
</script>