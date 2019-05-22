@extends('layouts.account')

@section('title', __('Account / Facturen'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Facturen') }}
    </h2>
@endsection

@section('account.content')
    <table class="table" id="invoice-table">
        <thead>
            <tr>
                <th class="w-50">{{ __('Factuurnummer') }}</th>
                <th class="w-25">
                    {{ __('Datum') }}

                    <span>
                        @if ((int) request('sort-order') === \WTG\Services\Import\Invoices::SORT_ORDER_ASC)
                            <i class="fas fa-fw fa-caret-up"></i>
                        @else
                            <i class="fas fa-fw fa-caret-down"></i>
                        @endif
                    </span>
                </th>
                <th class="w-25 text-right">{{ __('PDF Downloaden') }}</th>
            </tr>
        </thead>

        <tbody>
            @forelse(($invoices ?? []) as $key => $invoice)
                <tr>
                    <td>{{ $invoice->get('invoice') }}</td>
                    <td>{{ $invoice->get('date')->format('d-m-Y H:i:s') }}</td>
                    <td class="text-right">
                        <a href="#" onclick="openInvoice('{{ route("account.invoices.view", ["file" => $invoice->get("invoice")]) }}')"
                           class="btn btn-sm btn-outline-dark">
                            <i class="fa fa-fw fa-download"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <div class="alert alert-warning">
                            {{ __('Er zijn geen facturen gevonden voor uw account.') }}
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        function openInvoice(url) {
            window.open(
                url, '', 'location=no,scrollbars=yes,status=no,toolbar=yes,width=800,height=800'
            );
        }
    </script>
@endpush