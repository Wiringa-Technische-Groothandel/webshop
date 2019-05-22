@extends('layouts.account')

@section('title', __('Account / Bestelhistorie'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Bestelhistorie') }}
    </h2>
@endsection

@section('account.content')
    <table class="table" id="favorites-table">
        <thead>
            <tr>
                <th class="w-50">{{ __('Datum geplaatst') }}</th>
                <th class="w-25 text-right">{{ __('Prijs') }}</th>
                <th class="w-25 text-right">{{ __('PDF Downloaden') }}</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $order)
                @if (!$order->items)
                    @continue
                @endif

                <tr>
                    <td>{{ $order->getAttribute('created_at') }}</td>
                    <td class="text-right"><i class="fal fa-euro-sign"></i> {{ format_price($order->items->sum('subtotal')) }}</td>
                    <td class="text-right">
                        <form method="post">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $order->getAttribute('id') }}" name="order" />

                            <button class="btn btn-sm btn-outline-dark"><i class="fa fa-fw fa-download"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <div class="alert alert-warning">
                            {{ __('U hebt nog geen bestellingen geplaatst.') }}
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
