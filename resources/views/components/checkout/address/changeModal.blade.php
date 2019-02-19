<div class="modal fade" id="change-address-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Selecteer een adres") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <address id="address-0">
                                    <b>{{ __('Afhalen') }}</b><br />
                                    Bovenstreek 1<br />
                                    9731 DH, Groningen
                                </address>

                                <button class="btn btn-outline-primary btn-sm change-address-button"
                                        data-target="#address-0"
                                        data-address-id="0">
                                    {{ __("Selecteer adres") }}
                                </button>
                            </div>
                        </div>
                    </div>

                    @forelse ($addresses as $address)
                        <div class="col-xs-12 col-sm-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <address id="address-{{ $address->getId() }}">
                                        <b>{{ $address->getName() }}</b><br />
                                        {{ $address->getStreet() }} <br />
                                        {{ $address->getPostcode() }} {{ $address->getCity() }}
                                    </address>

                                    <button class="btn btn-outline-primary btn-sm change-address-button"
                                            data-target="#address-{{ $address->getId() }}"
                                            data-address-id="{{ $address->getId() }}">
                                        {{ __("Selecteer adres") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 col-md-8 offset-md-2">
                            <div class="alert alert-warning">
                                {{ __("U hebt nog geen addressen gekoppeld aan uw account.") }}
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Sluiten") }}</button>
            </div>
        </div>
    </div>
</div>