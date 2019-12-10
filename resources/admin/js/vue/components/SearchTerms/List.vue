<template>
    <div class="card" id="search-term-list">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-search"></i> Zoektermen
            </h3>

            <hr />

            <div class="row">
                <div class="col-8 offset-2">
                    <dl>
                        <dt>Zoektermen</dt>
                        <dd>Een of meer termen/woorden die een gebruiker invult bij het zoeken.
                            Gescheiden door een , (komma)
                        </dd>

                        <dt>Synoniemen</dt>
                        <dd>De waarde(s) waar de zoekmachine op gaat zoeken als deze een van de
                            zoektermen
                            tegen komt in de input van de gebruiker. Gescheiden door een , (komma)
                        </dd>

                        <dt>Voorbeeld</dt>
                        <dd>Als in het zoekterm veld '<b>t stuk, t-stuk</b>' is ingevuld en in het
                            synoniemen veld '<b>tstuk</b>' dan zoekt de zoekmachine op
                            '<b>viega tstuk</b>' wanneer er op '<b>viega t-stuk</b>' gezocht wordt.<br>
                            Het is ook mogelijk om meerdere synoniemen aan te geven. Als waarden
                            bijvoorbeeld omgedraaid zijn, dan zoekt de zoekmachine op
                            '<b>t stuk t-stuk</b>' als de gebruiker '<b>tstuk</b>' ingevuld heeft in de
                            zoekbalk.
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="row">
                <div class="col-4 offset-2 text-center">
                    <b>Zoektermen</b>
                </div>

                <div class="col-4 text-center">
                    <b>Synoniemen</b>
                </div>
            </div>

            <hr>

            <div class="row mb-3" v-for="searchTerm in searchTerms">
                <div class="col-4 offset-2">
                    <input class="form-control" v-model="searchTerm.source" placeholder="Zoektermen"/>
                </div>
                <div class="col-4">
                    <input class="form-control" v-model="searchTerm.target" placeholder="Synoniemen"/>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger" @click="deleteTerm(searchTerm)">
                        <i class="far fa-fw fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-8 offset-2 text-right">
                    <button @click="addTermField" class="btn btn-primary btn-raised" :disabled="loading">
                        <i class="far fa-fw fa-plus"></i> Regel toevoegen
                    </button>

                    <button @click="saveTerms" class="btn btn-success btn-raised" :disabled="loading">Opslaan</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                searchTerms: [],
                loading: false
            }
        },
        methods: {
            async fetchSearchTerms() {
                this.loading = true;

                await this.$http.get(route('admin.api.search-terms'))
                    .then(this._handleNotifyResponse)
                    .then(this._handleSearchTermResponse)
                    .catch(this._handleAxiosError);

                this.loading = false;
            },
            async deleteTerm(searchTerm) {
                if (! confirm('Weet u zeker dat u de zoekterm wilt verwijderen?')) {
                    return;
                }

                const index = this.searchTerms.findIndex(item => (
                    item.id === searchTerm.id &&
                    item.source === searchTerm.source &&
                    item.target === searchTerm.target
                ));

                if (searchTerm.id === null) {
                    this.searchTerms.splice(index, 1);

                    return;
                }

                this.loading = true;

                await this.$http.delete(route('admin.api.delete-search-term'), {
                    params: {
                        id: searchTerm.id
                    }
                })
                    .then(this._handleNotifyResponse)
                    .then(this._handleSearchTermResponse)
                    .catch(this._handleAxiosError);

                this.loading = false;
            },

            async saveTerms() {
                this.loading = true;

                await this.$http.post(route('admin.api.save-search-terms'), {
                    terms: this.searchTerms
                })
                    .then(this._handleNotifyResponse)
                    .then(this._handleSearchTermResponse)
                    .catch(this._handleAxiosError);

                this.loading = false;
            },
            addTermField() {
                this.searchTerms.push({
                    id: null,
                    source: '',
                    target: ''
                });
            },
            _handleNotifyResponse(response) {
                if (response.data.message) {
                    this.$root.$emit('send-notify', {
                        text: response.data.message,
                        success: response.data.success
                    });
                }

                return response;
            },
            _handleSearchTermResponse(response) {
                this.searchTerms = response.data.searchTerms;

                this.addTermField();

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
            this.fetchSearchTerms();
        }
    }
</script>