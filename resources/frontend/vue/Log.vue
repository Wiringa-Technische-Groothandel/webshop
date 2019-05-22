<template>
    <div id="logs">
        <div class="container">
            <div class="row">
                <div class="col-1">
                    <b>ID</b>
                </div>
                <div class="col-6">
                    <b>Bericht</b>
                </div>
                <div class="col-2">
                    <b>Level</b>
                </div>
                <div class="col-3">
                    <b>Datum</b>
                </div>
            </div>

            <hr />

            <div class="row" v-for="item in items" v-on:click="item.visible = !item.visible">
                <div class="col-1">
                    <p>{{ item.id }}</p>
                </div>
                <div class="col-6">
                    <p>{{ item.message }}</p>
                </div>
                <div class="col-2">
                    <p>{{ item.level_name }}</p>
                </div>
                <div class="col-3">
                    <p>{{ item.logged_at }}</p>
                </div>

                <div class="col-12" :class="{ 'd-none': !item.visible }">
                    <label>Context:</label>
                    <p>{{ item.context }}</p>

                    <br />

                    <label>Extra:</label>
                    <p>{{ item.extra }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['items'],
        mounted () {
            console.log(this.items);
        }
    }
</script>