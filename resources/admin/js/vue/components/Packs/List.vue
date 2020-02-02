<template>
    <div id="pack-list" class="card mb-3">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-list"></i> Actiepaketten
            </h3>

            <hr/>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Naam</th>
                    <th>Aantal producten</th>
                    <th>Aanmaakdatum</th>
                    <th>Wijzigingsdatum</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="(pack, index) in packs" @click="$emit('click', pack, index)">
                    <td>{{ pack.product.name }}</td>
                    <td>{{ pack.items.length }}</td>
                    <td>{{ pack.created_at }}</td>
                    <td>{{ pack.updated_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
    tr:hover {
        cursor: pointer;
    }
</style>

<script>
    export default {
        props: {
            packs: {
                required: true,
                type: Array
            }
        }
    }
</script>