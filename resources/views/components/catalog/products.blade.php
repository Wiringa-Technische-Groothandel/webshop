@foreach ($products as $product)
    <div class="product-listing" id="product-{{ $product->getId() }}">
        <div class="row">
            <div class="col-2 d-none d-sm-block">
                <div class="product-thumbnail">
                    <img src="{{ $product->getImageUrl() }}" class="img-thumbnail">
                </div>
            </div>

            <div class="col-8 col-sm-7">
                <a class="product-name d-block mb-2"
                   href="{{ routeIf('catalog.product', [ 'sku' => $product->getSku() ]) }}">
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

            <div class="col-4 col-sm-3 text-right">
                @auth
                    <price :product="{{ $product }}"></price>
                @endauth
            </div>
        </div>
    </div>

    <hr />
@endforeach
