@foreach ($products as $product)
    <div class="product-listing" id="product-{{ $product->getId() }}">
        <div class="row">
            <div class="col-2 d-none d-sm-block">
                <div class="product-thumbnail">
                    <img src="{{ $product->getImageUrl('small') }}" class="img-thumbnail">
                </div>
            </div>

            <div class="col-12 col-sm-7">
                <a class="product-name d-block mb-2"
                   href="{{ $product->getUrl() }}">
                    {{ $product->getName() }}
                </a>

                <div class="product-details d-block">
                    <small class="d-block">{{ __('Artikelnummer') }}: {{ $product->getSku() }}</small>
                    <small class="product-path">{{ $product->getPath() }}</small>
                </div>

                @auth
                    <add-to-cart sku="{{ $product->getSku() }}"
                                 sales-unit-single="{{ ucfirst(unit_to_str($product->getSalesUnit(), false)) }}"
                                 sales-unit-plural="{{ ucfirst(unit_to_str($product->getSalesUnit())) }}"
                                 submit-url="{{ route('checkout.cart') }}"></add-to-cart>
                @endauth
            </div>

            <div class="col-12 col-sm-3 text-right">
                @auth
                    <price :product="{{ $product }}"
                           auth-url="{{ route('auth.login', ['toUrl' => url()->current()]) }}"
                           :logged-in="{{ auth()->check() ? 'true' : 'false' }}"></price>
                @endauth
            </div>
        </div>
    </div>

    <hr />
@endforeach
