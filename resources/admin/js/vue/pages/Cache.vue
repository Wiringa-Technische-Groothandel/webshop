<template>
    <div id="page-cache">
        <div class="alert alert-danger">
            De PHP OPCache module is niet geladen of niet geinstalleerd.
        </div>
    </div>
</template>

<style scoped>
    .bmd-btn-fab {
        position: fixed;
        bottom: 20px;
        right: 20px;
    }
</style>

<script>
    export default {
        data () {
            return {
                enabled: false
            }
        }
    }
</script>