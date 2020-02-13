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
                    <div class="col-12 col-sm-6">
                        <h4>{{ __('Standaard adres') }}</h4>
                        <div class="row">
                            @if ($defaultAddress)
                                <div class="col">
                                    @include('components.checkout.address.change-modal.address', [ 'address' => $defaultAddress ])
                                </div>
                            @else
                                <div class="col">
                                    <div class="alert alert-info">
                                        <i class="fas fa-fw fa-info-circle"></i> {{ __("Geen standaard adres geselecteerd.") }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <h4>{{ __('Afhalen') }}</h4>
                        <div class="row">
                            <div class="col">
                                @include('components.checkout.address.change-modal.address', [ 'address' => $pickupAddress ])
                            </div>
                        </div>
                    </div>
                </div>

                @if (count($addresses) > 1)
                    <hr>

                    <h4>{{ __('Overige adressen') }}</h4>
                    <div class="row">
                        @foreach ($addresses as $address)
                            @continue($defaultAddress && $address->getId() === $defaultAddress->getId())
                            <div class="col-12 col-sm-6">
                                @include('components.checkout.address.change-modal.address', [ 'address' => $address ])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-dismiss="modal"
                        data-target="#address-modal">{{ __("Adres toevoegen") }}</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __("Sluiten") }}</button>
            </div>
        </div>
    </div>
</div>