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
                <h4>{{ __('Standaard adres') }}</h4>
                <div class="row">
                    @include('components.checkout.address.change-modal.address', [ 'address' => $defaultAddress ])
                </div>

                <hr>

                <h4>{{ __('Afhalen') }}</h4>
                <div class="row">
                    @include('components.checkout.address.change-modal.address', [ 'address' => $pickupAddress ])
                </div>

                <hr>

                @if (count($addresses) > 1)
                    <h4>{{ __('Overige adressen') }}</h4>
                    <div class="row">
                        @foreach ($addresses as $address)
                            @continue($address->getId() === $defaultAddress->getId())
                            @include('components.checkout.address.change-modal.address', [ 'address' => $address ])
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Sluiten") }}</button>
            </div>
        </div>
    </div>
</div>