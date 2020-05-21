<price
        :product="{{ $product }}"
        :logged-in="{{ auth()->check() ? 'true' : 'false' }}"
        auth-url="{{ route('auth.login', ['toUrl' => url()->current()]) }}"
></price>

@if ($product->isDiscontinued())
    <br/>

    <div class="text-danger">
        <i class="fas fa-fw fa-exclamation-triangle"></i> {{ __("Dit is een uitlopend artikel") }}
    </div>
@endif

@auth
    <br/>

    <div class="row">
        <div class="col-12 mb-3">
            <add-to-cart sku="{{ $product->getSku() }}"
                         :step="{{ $product->getMinimalPurchaseAmount() }}"
                         sales-unit-single="{{ ucfirst(unit_to_str($product->getSalesUnit(), false)) }}"
                         sales-unit-plural="{{ ucfirst(unit_to_str($product->getSalesUnit())) }}"
                         submit-url="{{ route('checkout.cart') }}"></add-to-cart>
        </div>

        <div class="col-12">
            <favorites-toggle-button sku="{{ $product->getSku() }}"
                                     check-url="{{ route('favorites.check') }}"
                                     toggle-url="{{ route('favorites.toggle') }}"></favorites-toggle-button>
        </div>
    </div>
@endauth
