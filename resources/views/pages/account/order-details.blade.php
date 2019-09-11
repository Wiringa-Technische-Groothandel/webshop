@extends('layouts.account')

@section('title', __('Account / Order details'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Order details') }}
    </h2>
@endsection

@section('account.content')
    <div class="row">
        <div class="col-6">
            <a href="{{ route('account.order-history') }}" class="btn btn-link">
                <i class="fa fa-fw fa-chevron-left"></i> {{ __('Terug naar overzicht') }}
            </a>
        </div>
        <div class="col-6">
            <form method="post" class="mb-3 text-right">
                @csrf

                <button type="submit" class="btn btn-outline-success">
                    {{ __('Producten opnieuw bestellen') }}
                </button>
            </form>
        </div>
    </div>

    <small class="text-muted mb-3 d-block text-right">
        {{ __('Producten die niet meer in de webshop staan zullen niet toegevoegd worden aan de winkelwagen') }}
    </small>

    <table class="table" id="order-details-table">
        <thead>
        <tr>
            <th>{{ __("Productnummer") }}</th>
            <th>{{ __("Naam") }}</th>
            <th>{{ __("Prijs") }}</th>
            <th>{{ __("Aantal") }}</th>
            <th>{{ __("Subtotaal") }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($order->items as $item)
            @php
                /** @var \WTG\Models\Product $product */
                $product = $item->getProduct()
            @endphp

            <tr>
                <td style="white-space: nowrap">
                    @if ($product)
                        <a href="{{ $product->getUrl() }}">{{ $item->getAttribute('sku') }}</a>
                    @else
                        <span style="text-decoration: line-through;">{{ $item->getAttribute('sku') }}</span>
                    @endif
                </td>
                <td>{{ $item->getAttribute('name') }}</td>
                <td style="white-space: nowrap">
                    <i class="far fa-fw fa-euro-sign"></i>{{ format_price($item->getAttribute('price')) }}
                </td>
                <td style="white-space: nowrap">{{ $item->getAttribute('qty') }}</td>
                <td style="white-space: nowrap">
                    <i class="far fa-fw fa-euro-sign"></i>
                    {{ format_price($item->getAttribute('price') * $item->getAttribute('qty')) }}
                </td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
            <tr style="font-size: 1.7em;">
                <td colspan="2" class="text-right"><b>{{ __('Totaalprijs') }}</b></td>
                <td colspan="3" class="text-center">
                    <i class="far fa-fw fa-euro-sign"></i> {{ format_price($order->getGrandTotal()) }}
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
