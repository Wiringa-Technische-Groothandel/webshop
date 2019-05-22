
<div class="col-12">
    <div class="row cart-item">
        <div class="col-10 col-sm-5 col-lg-6">
            <div class="cart-item-name">
                <a href="{{ route('product', ['sku' => $item->sku()]) }}">
                    {{ $item->product()->name() }}
                </a>
            </div>
        </div>

        <div class="col-2 col-sm-1 order-sm-2">
            <div class="cart-item-delete text-right">
                <button type="submit" class="btn btn-link" onclick="cart.delete(this)"
                        data-delete-url="{{ route('checkout.cart.delete', ['item' => $item->getAttribute('id')]) }}">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="col-4 col-sm-2 order-sm-1 d-none d-md-block d-lg-block d-xl-block">
            <div class="cart-item-price text-left">
                {{ format_price(1000) }}
            </div>
        </div>

        <div class="col-4 col-md-2 col-lg-1 col-sm-3 order-sm-1">
            <div class="cart-item-qty text-right">
                <input type="number" class="form-control" placeholder="Aantal" min="1" step="1"
                       value="{{ $item->quantity() }}" oninput="cart.update(this)"
                       data-update-url="{{ route('checkout.cart.update', ['item' => $item->getAttribute('id')]) }}" />
            </div>
        </div>

        <div class="col-4 col-sm-2 order-sm-1">
            <div class="cart-item-subtotal text-right">
                {{ format_price(1000) }}
            </div>
        </div>
    </div>
</div>