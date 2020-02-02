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
                    data-address-id="{{ $address->getId() }}"
                    onclick="window.updateShippingAddress(this)">
                {{ __("Selecteer adres") }}
            </button>
        </div>
    </div>
</div>