<template>
    <div id="mini-cart">
        <a class="nav-link" :href="cartUrl">
            <i class="far fa-fw fa-shopping-cart"></i> <span class="badge badge-danger">{{ itemCount }}</span>
        </a>
    </div>
</template>

<style scoped lang="scss">
    #mini-cart {
        position: relative;

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            border-radius: 1rem;
            font-weight: 100;
        }
    }
</style>

<script>
    export default {
        props: ['count', 'cartUrl'],
        data () {
            return {
                itemCount: this.count
            };
        },
        mounted () {
            this.$root.$on('update-cart-count', (count) => { // here you need to use the arrow function
                this.$data.itemCount = count;
            });
        }
    }
</script>