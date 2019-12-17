<template>
    <div id="pack-component">
        <h3 :title="pack.product.name" class="title">
            <i class="fal fa-fw fa-box"></i> {{ pack.product.sku }} - {{ pack.product.name }}
        </h3>

        <hr/>

        <div class="product-list mb-3">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Product</th>
                    <th></th>
                    <th>Aantal</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="item in pack.items" @click="confirmItemDelete(item)">
                    <td>{{ item.product.sku }}</td>
                    <td>{{ item.product.name }}</td>
                    <td>{{ item.amount }}</td>
                </tr>
                </tbody>
            </table>

            <hr>

            <div class="row">
                <div class="col">
                    <label>Artikelnummer</label>
                    <input class="form-control" v-model="sku" required>
                </div>
                <div class="col">
                    <label>Aantal</label>
                    <input class="form-control" type="number" v-model="amount" required>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col">
                <button class="btn btn-outline-danger btn-block" @click="confirmDelete">
                    <i class="far fa-fw fa-trash-alt"></i> Verwijderen
                </button>
            </div>

            <div class="col">
                <button class="btn btn-outline-primary btn-block" @click="addProduct" :disabled="!dirty">
                    <i class="far fa-fw fa-plus"></i> Product toevoegen
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    tr:hover {
        cursor: pointer;
    }
</style>

<script>
    export default {
        props: {
            pack: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                sku: '',
                amount: '',
                dirty: false
            }
        },
        watch: {
            pack() {
                this.sku = '';
                this.amount = '';
            },
            sku() {
                this.checkIfDirty()
            },
            amount() {
                this.checkIfDirty()
            }
        },
        methods: {
            checkIfDirty() {
                this.dirty = this.sku !== '' && this.amount !== '';
            },
            addProduct() {
                this.$emit('add-item', {
                    id: this.pack.id,
                    sku: this.sku,
                    amount: this.amount
                });
            },
            confirmItemDelete(item) {
                const del = confirm(`Weet u zeker dat u product '${item.product.name}' uit het actiepaket wilt verwijderen?`);

                if (!del) {
                    return;
                }

                this.$emit('delete-item', item);
            },
            confirmDelete() {
                const del = confirm(`Weet u zeker dat u actiepakket '${this.pack.product.name}' wilt verwijderen?`);

                if (!del) {
                    return;
                }

                this.$emit('delete', this.pack);
            }
        }
    }
</script>