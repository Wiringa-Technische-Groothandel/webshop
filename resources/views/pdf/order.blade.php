@extends('layouts.pdf')

@section('title')
    <h2 class="py-3 d-inline-block">{{ __("Webshop order") }}</h2>
@endsection

@section('pre-content')
    <div class="row mb-3 clearfix">
        <div class="col-6 float-left">
            @if ($order->getAttribute('comment'))
                <div class="card">
                    <div class="card-header">
                        {{ __("Opmerking") }}
                    </div>
                    <div class="card-body">
                        <p>{{ $order->getAttribute('comment') }}</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-6 float-right">
            <div class="card">
                <div class="card-header">
                    {{ __("Afleveradres") }}
                </div>
                <div class="card-body">
                    <address>
                        <b>{{ $order->getAttribute('name') }}</b><br />
                        {{ $order->getAttribute('street') }}<br />
                        {{ $order->getAttribute('postcode') }} {{ $order->getAttribute('city') }}
                    </address>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <table class="table">
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
            <tr>
                <td style="white-space: nowrap">{{ $item->getAttribute('sku') }}</td>
                <td>{{ $item->getAttribute('name') }}</td>
                <td style="white-space: nowrap">{{ format_price($item->getAttribute('price')) }}</td>
                <td style="white-space: nowrap">{{ $item->getAttribute('qty') }}</td>
                <td style="white-space: nowrap">{{ format_price($item->getAttribute('price') * $item->getAttribute('qty')) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection