<template>
    <form class="form-inline my-2 my-lg-0" id="quick-search" ref="form" :action="searchUrl">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Zoeken" name="query"
                   v-model="inputQuery" @input="search" />

            <span class="input-group-append">
                <button class="btn btn-outline-success" type="submit">
                    <i class="far fa-fw fa-search"></i>
                </button>
            </span>
        </div>

        <div class="card" v-if="totalItems > 0">
            <div class="card-header">
                Suggesties
                <i class="float-right far fa-fw fa-times" v-on:click="reset"></i>
            </div>

            <div class="list-group">
                <a class="list-group-item list-group-item-action" v-for="item in items" :href="item.url">
                    {{ item.name }}
                </a>

                <a class="list-group-item list-group-item-action text-center" v-if="totalItems > 4"
                   href="#" @click="$refs.form.submit()">
                    <span class="show-all">Alle resultaten bekijken</span>
                </a>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            searchUrl: {
                required: true,
                type: String
            },
            query: {
                required: false,
                type: String,
                default () {
                    return '';
                }
            }
        },
        data () {
            return {
                inputQuery: this.query,
                totalItems: 0,
                items: []
            }
        },
        methods: {
            reset() {
                this.items = [];
                this.totalItems = 0;
            },
            search () {
                let query = this.inputQuery;

                if (query.length < 1) {
                    this.$data.showSuggestions = false;

                    return;
                }

                axios.post(this.searchUrl, {
                        query: query
                    })
                    .then((response) => {
                        if (query !== this.inputQuery) {
                            return;
                        }

                        this.items = response.data.products;
                        this.totalItems = this.items.length;
                        this.$data.showSuggestions = response.data.totalItems > 0;
                    })
                    .catch((error) => {
                        this.$root.$emit('send-notify', {
                            text: error.response.data.message,
                            success: false
                        });
                    });
            }
        }
    }
</script>