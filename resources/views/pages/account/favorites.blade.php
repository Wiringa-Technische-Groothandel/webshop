@extends('layouts.account')

@section('title', __('Account / Favorieten'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ trans('titles.account.favorites') }}
    </h2>
@endsection

@section('account.content')
    <table class="table" id="favorites-table">
        <thead>
            <tr>
                <th width="40">
                    <button class="btn btn-link p-0" data-url="{{ routeIf('account.favorites.addToCart') }}"
                            onclick="addSelectedToCart(this)">
                        <i class="fal fa-cart-plus fa-fw fa-2x"></i>
                    </button>
                </th>
                <th>{{ __('Productnaam') }}</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @forelse($favorites as $favorite)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" title="{{ __('Toevoegen aan winkelwagen.') }}"
                               data-product="{{ $favorite->getAttribute('id') }}">
                    </td>
                    <td>
                        <a href="{{ route('catalog.product', ['sku' => $favorite->getAttribute('sku')]) }}">
                            {{ $favorite->getAttribute('name') }}
                        </a>
                    </td>
                    <td class="text-right" style="padding: 5px;">
                        <form method="post" action="{{ route('favorites.delete') }}">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <input type="hidden" value="{{ $favorite->getAttribute('sku') }}" name="sku" />

                            <button class="btn btn-link" type="submit">
                                <i class="fal fa-fw fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <div class="alert alert-warning">
                            {{ __('U hebt nog geen favorieten toegevoegd.') }}
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('extraJS')
    <script>
        /**
         * Add the selected items to the cart.
         *
         * @return void
         */
        function addSelectedToCart(button) {
            event.preventDefault();

            var products = [];
            var $target = $(button);
            var $table = $('#favorites-table');
            var $selectedItems = $table.find('input:checked');

            if ($selectedItems.length === 0) {
                console.log('No items selected');

                return;
            }

            $selectedItems.each(function () {
                this.checked = false;

                products.push(this.getAttribute('data-product'));
            });

            if (products.length === 0) {
                alert('{{ __('Er is iets mis gegaan, de error melding is: products is empty while selected items were found!') }}');
            }

            axios.put($target.data('url'), {
                    'products': products
                })
                .then(function (response) {
                    console.log(response);
                    var errors = response.data.errors;
                    var message = response.data.message;

                    if (errors.length) {
                        window.vm.$root.$emit('send-notify', {
                            text: errors.join("<br />"),
                            success: false
                        });
                    }

                    if (message) {
                        window.vm.$root.$emit('send-notify', {
                            text: message,
                            success: true
                        });
                    }

                    window.vm.$root.$emit('update-cart-qty', response.data.cartQty);
                })
                .catch(function (response) {
                    alert(response);
                });
        }
    </script>
@endsection