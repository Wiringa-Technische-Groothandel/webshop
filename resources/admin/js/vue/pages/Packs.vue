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
                    <pack-list :packs="packs" @click="showPack"></pack-list>
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
    import PackList from '../components/Packs/List'

    export default {
        components: {
            CreatePackModal,
            Pack,
            PackList
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