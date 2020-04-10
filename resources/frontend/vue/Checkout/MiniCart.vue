<template>
    <div id="mini-cart">
        <a class="nav-link" :href="cartUrl">
            <i class="far fa-fw fa-shopping-cart d-none d-md-inline"></i>
            <span class="d-inline d-md-none">Winkelwagen</span>

            <span class="badge badge-danger">{{ itemCount }}</span>
        </a>
    </div>
</template>

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
