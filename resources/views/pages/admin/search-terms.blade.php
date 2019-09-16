@extends('layouts.admin')

@section('title', __('Productbeheer'))

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-12">
                <form action="{{ route('admin.search-terms.save') }}" method="post">
                    {{ csrf_field() }}

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8 offset-2">
                                    <dl>
                                        <dt>{{ __('Zoektermen') }}</dt>
                                        <dd>Een of meer termen/woorden die een gebruiker invult bij het zoeken.
                                            Gescheiden door een , (komma)
                                        </dd>

                                        <dt>{{ __('Synoniemen') }}</dt>
                                        <dd>De waarde(s) waar de zoekmachine op gaat zoeken als deze een van de
                                            zoektermen
                                            tegen komt in de input van de gebruiker. Gescheiden door een , (komma)
                                        </dd>

                                        <dt>{{ __('Voorbeeld') }}</dt>
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
                                    <b>{{ __('Zoektermen') }}</b>
                                </div>
                                <div class="col-4 text-center">
                                    <b>{{ __('Synoniemen') }}</b>
                                </div>
                            </div>
                            <hr>

                            <div id="synonym-list">
                                @foreach ($synonyms as $synonym)
                                    <div class="row mb-3" id="term-{{ $synonym->getId() }}">
                                        <div class="col-4 offset-2">
                                            <input class="form-control" name="terms[{{ $synonym->getId() }}][source]"
                                                   value="{{ $synonym->getSource() }}"
                                                   placeholder="{{ __('Zoektermen') }}"/>
                                        </div>
                                        <div class="col-4">
                                            <input class="form-control" name="terms[{{ $synonym->getId() }}][target]"
                                                   value="{{ $synonym->getTarget() }}"
                                                   placeholder="{{ __('Synoniemen') }}"/>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-danger"
                                                    onclick="deleteSynonym('{{ route('admin.search-terms.delete', ['term' => $synonym->getId()]) }}', {{ $synonym->getId() }})">
                                                <i class="far fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mb-3" id="template" style="display: none;">
                                <div class="col-4 offset-2">
                                    <input class="form-control source" placeholder="{{ __('Zoektermen') }}"/>
                                </div>
                                <div class="col-4">
                                    <input class="form-control target" placeholder="{{ __('Synoniemen') }}"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8 offset-2 text-right">
                                    <button type="button" onclick="addSynonymField()" class="btn btn-primary btn-raised">
                                        <i class="far fa-fw fa-plus"></i> {{ __('Regel toevoegen') }}
                                    </button>
                                    <button type="submit" class="btn btn-success btn-raised">
                                        {{ __('Opslaan') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var $synonymTemplate = $('#template');
        var $synonymList = $('#synonym-list');
        var newFieldIndex = 0;

        /**
         * @param {String} url
         * @param {Number} id
         */
        function deleteSynonym(url, id) {
            if (!confirm('{{ __('Weet u zeker dat u de zoekterm wilt verwijderen?') }}')) {
                return;
            }

            axios.delete(url).then(function (response) {
                $('#term-' + id).remove();

                vm.$root.$emit('send-notify', { text: response.message, success: true });
            }).catch(function (err) {
                vm.$root.$emit('send-notify', { text: err, success: false });
            });
        }

        function addSynonymField() {
            var $newFieldSet = $synonymTemplate.clone();

            $newFieldSet.find('.source').attr('name', 'new-terms[' + newFieldIndex + '][source]');
            $newFieldSet.find('.target').attr('name', 'new-terms[' + newFieldIndex + '][target]');

            $newFieldSet.removeAttr('id')
                .show()
                .appendTo($synonymList);

            newFieldIndex = newFieldIndex + 1;
        }
        addSynonymField();
    </script>
@endpush