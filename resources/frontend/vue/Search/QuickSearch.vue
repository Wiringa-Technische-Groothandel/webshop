<template>
    <form class="form-inline my-2 my-lg-0" id="quick-search" :action="searchUrl">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Zoeken" name="query"
                   v-model="inputQuery" @input="search" />

            <span class="input-group-append">
                <button class="btn btn-outline-success" type="submit">
                    <i class="far fa-fw fa-search"></i>
                </button>
            </span>
        </div>

        <div class="card" v-if="showSuggestions">
            <div class="card-header">
                Suggesties
                <i class="float-right far fa-fw fa-times" v-on:click="showSuggestions = false"></i>
            </div>

            <div class="list-group">
                <a class="list-group-item list-group-item-action" v-for="item in items" :href="item.url">
                    {{ item.name }}
                </a>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['query', 'searchUrl'],
        methods: {
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

                        let products = response.data.products;

                        this.$data.items = products;
                        this.$data.showSuggestions = products.length > 0;
                    })
                    .catch((error) => {
                        this.$root.$emit('send-notify', {
                            text: error.response.data.message,
                            success: false
                        });
                    });
            }
        },
        data () {
            return {
                inputQuery: this.query,
                showSuggestions: false,
                items: []
            }
        }
    }
</script>