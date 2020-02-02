<template>
    <nav id="side-navigation" class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="navbar-brand mb-3">
                    <a class="nav-link p-0" href="/">
                        <img height="45" width="150" class="d-block mx-auto" src="/storage/static/images/nav-logov2.png"/>
                    </a>
                </li>

                <li class="nav-item" v-for="route in routes">
                    <a class="nav-link" :class="{active: isActive(route)}" @click="changeRoute(route)">
                        <i class="fal fa-fw" :class="route.meta.icon"></i> {{ route.meta.name }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<style scoped>
    .nav-item:hover {
        cursor: pointer;
    }
</style>

<script>
    export default {
        computed: {
            routes () {
                const routes = this.$router.options.routes;

                return routes.filter(route => !route.meta.hideFromNavigation);
            }
        },
        methods: {
            changeRoute (route) {
                if (route.path === this.$route.path) {
                    return false;
                }

                this.$router.push(route);
            },

            isActive (route) {
                if (
                    route.path === this.$route.path ||
                    (route.name && route.name === this.$route.name)
                ) {
                    return true;
                }

                if (route.children) {
                    let index = route.children.findIndex(
                        child => child.path === this.$route.path || (child.name && child.name === this.$route.name)
                    );

                    if (index > -1) {
                        return true;
                    }
                }

                return false;
            }
        }
    }
</script>