<price
        :product="{{ $product }}"
        :logged-in="{{ auth()->check() ? 'true' : 'false' }}"
        auth-url="{{ route('auth.login', ['toUrl' => url()->current()]) }}"
></price>

@auth
    <br />

    <div class="row">
        <div class="col-12 mb-3">
            <add-to-cart sku="{{ $product->getSku() }}"
                         sales-unit-single="{{ ucfirst(unit_to_str($product->getSalesUnit(), false)) }}"
                         sales-unit-plural="{{ ucfirst(unit_to_str($product->getSalesUnit())) }}"
                         submit-url="{{ route('checkout.cart') }}"></add-to-cart>
        </div>

        <div class="col-12 col-md-2">
            <favorites-toggle-button sku="{{ $product->getSku() }}"
                                     check-url="{{ route('favorites.check') }}"
                                     toggle-url="{{ route('favorites.toggle') }}"></favorites-toggle-button>
        </div>
    </div>
@endauth