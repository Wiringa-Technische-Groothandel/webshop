<template>
    <nav class="navbar navbar-dark sticky-top" id="top-navigation">
        <ul class="navbar-nav ml-auto flex-row">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="/">
                    <i class="fas fa-fw fa-home"></i> Home
                </a>
            </li>
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#" @click.prevent="logout">
                    <i class="fas fa-fw fa-power-off"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</template>

<style lang="scss" scoped>
    #top-navigation {
        background-color: #F01030;

        &.navbar-dark {
            .navbar-nav {
                .nav-link {
                    color: rgba(255, 255, 255, 0.8);
                }
            }
        }
    }
</style>

<script>
    export default {
        methods: {
            logout () {
                console.log('logout');
                this.$store.dispatch('logout');
            }
        }
    }
</script>