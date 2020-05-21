<template>
    <div id="notification-wrapper">
        <div v-for="message in messages">
            <transition enter-active-class="animated fadeInLeft" leave-active-class="animated fadeOutLeft" mode="out-in">
                <div class="notification d-flex" v-on:click="message.show = false" v-if="message.show"
                     :class="{ success: message.success, danger: !message.success }">
                    <i class="fal fa-fw" :class="{ 'fa-check-circle': message.success, 'fa-times-octagon': !message.success}"></i>
                    <span class="ml-3" v-html="message.text"></span>
                </div>
            </transition>
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
                    show: false
                };

                this.$data.messages.push(notification);

                this.$nextTick(() => {
                    notification.show = true;

                    // Auto hide success messages after 5 seconds, errors after 10
                    setTimeout(() => {
                        notification.show = false;
                    }, success ? 5000 : 10000);
                });
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
            font-size: 17px;
            line-height: 17px;
            font-weight: 300;
            border-radius: 4px;

            &.success {
                background: var(--success);
                color: white;
            }

            &.danger {
                background: var(--danger);
                color: white;
            }
        }
    }
</style>
