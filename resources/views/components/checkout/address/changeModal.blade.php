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
                    @foreach ($addresses as $address)
                        <div class="col-xs-12 col-sm-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <address id="address-{{ $address->getId() }}">
                                        <b>{{ $address->getName() }}</b><br>
                                        {{ $address->getStreet() }} <br>
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
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Sluiten") }}</button>
            </div>
        </div>
    </div>
</div>