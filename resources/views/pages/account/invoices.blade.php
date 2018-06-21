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
                <th class="w-25">{{ __('Datum') }}</th>
                <th class="w-25"></th>
            </tr>
        </thead>

        <tbody>
            @forelse(($invoices->get('invoices') ?? []) as $key => $invoice)
                <tr>
                    <td>
                        <a href="{{ route('account.invoices.view', ['file' => $key]) }}" class="btn btn-sm btn-outline-dark"><i class="fa fa-fw fa-download"></i></a>
                    </td>
                    <td>{{ $invoice }}</td>
                    <td></td>
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