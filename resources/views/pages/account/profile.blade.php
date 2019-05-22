@extends('layouts.account')

@section('title', __('Account / Mijn Account'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Welkom, :company', [ 'company' => $customer->getCompany()->getName() ]) }}
    </h2>
@endsection

@section('account.content')
    <div class="row mb-3">
        <div class="col-12 col-sm-9 mb-3">
            <div class="card">
                <div class="card-header">
                    {{ __('Uw profiel') }}
                </div>

                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>Contact Email</th>
                            <td>{{ $customer->getContact()->getContactEmail() ?: __('Geen') }}</td>
                        </tr>
                        <tr>
                            <th>Order Email</th>
                            <td>{{ $customer->getContact()->getOrderEmail() ?: __('Geen') }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="card-body">
                    <a href="{{ route('account.profile.edit') }}" class="btn btn-primary pull-right">
                        {{ __('Wijzigen') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
