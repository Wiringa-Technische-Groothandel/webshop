@extends('layouts.account')

@section('title', __('Account / Bestelhistorie'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Bestelhistorie') }}
    </h2>
@endsection

@section('account.content')
    <table class="table" id="orders-table">
        <thead>
        <tr>
            <th class="w-50">{{ __('Datum geplaatst') }}</th>
            <th class="w-25 text-right">{{ __('Prijs') }}</th>
            <th class="w-25 text-right">{{ __('Acties') }}</th>
        </tr>
        </thead>

        <tbody>
        @forelse($orders as $order)
            @if (!$order->items)
                @continue
            @endif

            <tr>
                <td>{{ $order->getAttribute('created_at') }}</td>
                <td class="text-right">
                    <i class="fal fa-euro-sign"></i> {{ format_price($order->items->sum('subtotal')) }}
                </td>
                <td class="text-right">
                    <a href="{{ route('account.order.view', ['uuid' => $order->getUuid() ]) }}"
                       title="{{ __('Bekijk bestelling') }}"
                       class="btn btn-sm btn-outline-info">
                        <i class="fa fa-fw fa-eye"></i>
                    </a>

                    <a class="btn btn-sm btn-outline-dark"
                       onclick="openOrder('{{ route('account.order.download', ['uuid' => $order->getUuid()]) }}')"
                       title="{{ __('PDF Downloaden') }}">
                        <i class="fa fa-fw fa-download"></i>
                    </a>
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

@push('scripts')
    <script>
        function openOrder(url) {
            window.open(
                url, '', 'location=no,scrollbars=yes,status=no,toolbar=yes,width=800,height=800'
            );
        }
    </script>
@endpush
