
<div class="col-12">
    <div class="row cart-footer">
        <div class="col-7 col-sm-9">
            <div class="cart-grand-total-text text-right">
                <b>{{ __('Totaalprijs') }}</b><br />
                <span class="small">{{ __('Prijs excl. BTW') }}</span>
            </div>
        </div>

        <div class="col-5 col-sm-2">
            <div class="cart-grand-total text-right">
                {{ format_price(1000) }}
            </div>
        </div>
    </div>

    <div class="row cart-footer-buttons">
        <div class="col-12 col-md-4 order-2 order-md-1 mb-3">
            <button class="btn btn-outline-danger d-block d-sm-inline" onclick="cart.destroy(this)"
                    data-destroy-url="{{ route('checkout.cart.destroy') }}">
                <i class="fa fa-trash-o"></i> {{ __('Winkelwagen legen') }}
            </button>
        </div>

        <div class="col-12 col-md-8 mr-auto order-1 order-md-2 mb-3 text-right">
            <div class="btn-group">
                <a class="btn btn-outline-primary d-block d-sm-inline" href="{{ session('checkout.continue') ?: url('webshop') }}">
                    <i class="fa fa-arrow-circle-left"></i> {{ __('Verder winkelen') }}
                </a>

                @if ($cart->count() > 0)
                    <a class="btn btn-outline-success d-block d-sm-inline" href="{{ route('checkout.address') }}">
                        {{ __('Volgende stap') }} <i class="fa fa-arrow-circle-right fa-fw"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>