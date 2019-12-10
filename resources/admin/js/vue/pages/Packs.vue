<template>
    <div id="page-packs" class="container-fluid">
        <create-pack-modal :show="showModal" @pack-created="fetchPacks"
                           @close="showModal = false" @hide="showModal = false"></create-pack-modal>

        <button class="btn btn-success bmd-btn-fab tooltip-toggle" data-toggle="modal"
                @click="showModal = !showModal" title="Actiepaket toevoegen">
            <i class="fal fa-fw fa-plus"></i>
        </button>

        <div class="row mb-3">
            <template v-if="packs.length > 0">
                <div class="col-md-7">
                    <div class="card mb-3">
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
                                <tr v-for="(pack, index) in packs" @click="showPack(pack, index)">
                                    <td>{{ pack.product.name }}</td>
                                    <td>{{ pack.items.length }}</td>
                                    <td>{{ pack.created_at }}</td>
                                    <td>{{ pack.updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-5" v-if="pack">
                    <div class="card mb-3">
                        <div class="card-body">
                            <pack :pack="pack" @delete="deletePack(pack)"
                                  @delete-item="deletePackItem" @add-item="addPackItem"></pack>
                        </div>
                    </div>
                </div>
            </template>

            <div class="col-sm-6 offset-sm-3" v-else>
                <div class="alert alert-info">
                    Geen actiepakketten gevonden.
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .bmd-btn-fab {
        position: fixed !important;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
</style>

<script>
    import CreatePackModal from '../components/Packs/CreateModal'
    import Pack from '../components/Packs/Pack'

    export default {
        components: {
            CreatePackModal,
            Pack
        },
        data() {
            return {
                showModal: false,
                pack: null,
                packIndex: null,
                packs: []
            }
        },
        methods: {
            showPack(pack, index) {
                this.pack = pack;
                this.packIndex = index;
            },
            async fetchPacks() {
                this.$http.get(route('admin.api.packs'))
                    .then((response) => {
                        if (response.data.packs) {
                            this.packs = response.data.packs;
                        }
                    })
                    .catch(this._handleAxiosError);
            },
            deletePack(pack) {
                this.sendDeleteRequest(route('admin.api.delete-pack'), pack.id)
                    .then(() => {
                        this.pack = null;
                        this.packIndex = null;
                    });
            },
            deletePackItem(item) {
                this.sendDeleteRequest(route('admin.api.delete-pack-item'), item.id)
                    .then(() => {
                        this.pack = this.packs[this.packIndex];
                    });
            },
            addPackItem({id, sku, amount}) {
                this.$http.post(route('admin.api.create-pack-item'), {
                    id, sku, amount
                })
                    .then(this._handleAxiosResponse)
                    .then(() => {
                        this.pack = this.packs[this.packIndex];
                    });
            },
            sendDeleteRequest(url, id) {
                return this.$http.delete(url, {
                    params: {
                        id
                    }
                })
                    .then(this._handleAxiosResponse)
                    .catch(this._handleAxiosError);
            },
            _handleAxiosResponse(response) {
                if (response.data.packs) {
                    this.packs = response.data.packs;
                }

                this.$root.$emit('send-notify', {
                    text: response.data.message,
                    success: response.data.success
                });

                return response;
            },
            _handleAxiosError(error) {
                console.error(error);

                this.$root.$emit('send-notify', {
                    text: error,
                    success: false
                });
            }
        },
        mounted() {
            this.fetchPacks();
        }
    }
</script>