<template>
    <div id="notification-wrapper">
        <div v-for="message in messages" class="notification animated" v-on:click="message.show = false"
             :class="{ success: message.success, danger: !message.success, fadeInLeft: message.show, fadeOutLeft: !message.show }">
            {{ message.text }} <br />
        </div>
    </div>
</template>

<script>
    export default {
        props: ['phpErrors', 'phpSuccess'],
        data () {
            return {
                messages: []
            }
        },
        methods: {
            addMessage (success, message) {
                let text;

                if (message.constructor === Object) {
                    message = Object.values(message);
                }

                if (message.constructor === Array) {
                    text = message.join('<br />');
                } else {
                    text = message;
                }

                let notification = {
                    success: success,
                    text: text,
                    show: true
                };

                this.$data.messages.push(notification);

                setTimeout(() => {
                    notification.show = false;
                }, 5000);
            }
        },
        created() {
            this.$root.$on('send-notify', (notification) => {
                this.addMessage(notification.success, notification.text);
            });
        },
        mounted () {
            this.phpErrors.forEach((message) => {
                this.addMessage(false, message);
            });

            if (this.phpSuccess) {
                this.addMessage(true, this.phpSuccess);
            }
        }
    }
</script>

<style lang="scss">
    #notification-wrapper {
        position: fixed;
        z-index: 999;
        bottom: 0;
        width: 0;
        left: 0;

        .notification {
            width: 100vw;
            display: inline-block;
            margin-top: 10px;
            padding: 20px;
            font-size: 15px;
            font-weight: 300;
            border-radius: 4px;

            &:before {
                font-family: "Font Awesome 5 Pro", sans-serif;
                margin: 0 10px 0 0;
            }

            &.success {
                background: green;
                color: white;

                &:before {
                    content: "\f058"; // check-circle
                }
            }

            &.danger {
                background: red;
                color: white;

                &:before {
                    content: "\f2f0"; // times-octagon
                }
            }

            &.warning {
                background: yellow;
                color: white;

                &:before {
                    content: "\f071"; // exclamation-triangle
                }
            }
        }
    }
</style>
