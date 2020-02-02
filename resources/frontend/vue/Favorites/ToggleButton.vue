<template>
    <div id="favorite-button">
        <button class="btn" :class="{'btn-default': !checked, 'btn-success': !isFavorite, 'btn-danger': isFavorite}" @click="this.toggle">
            <span class="far fa-fw fa-heart"></span> <span v-if="checked">{{ buttonText}}</span>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['sku', 'checkUrl', 'toggleUrl'],
        methods: {
            toggle () {
                axios.patch(this.toggleUrl, {
                    sku: this.sku
                })
                    .then((response) => {
                        if (response.data.success) {
                            this.$data.isFavorite = response.data.added;
                            this.$data.buttonText = response.data.buttonText;

                            this.$root.$emit('send-notify', {
                                text: response.data.notificationText,
                                success: true
                            });
                        }
                    })
            }
        },
        data () {
            return {
                checked: false,
                isFavorite: false,
                buttonText: ''
            };
        },
        mounted () {
            axios.post(this.checkUrl, {
                sku: this.sku
            })
                .then((response) => {
                    this.$data.checked = true;
                    this.$data.isFavorite = response.data.isFavorite;
                    this.$data.buttonText = response.data.buttonText;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
</script>