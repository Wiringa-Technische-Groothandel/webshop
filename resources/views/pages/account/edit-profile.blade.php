@extends('layouts.account')

@section('title', __('Account / Mijn Account Wijzigen'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Welkom, :company', [ 'company' => $customer->getCompany()->getName() ]) }}
    </h2>
@endsection

@section('account.content')
    <div class="row mb-3">
        <div class="col-12 col-sm-9 mb-3">
            <form method="post">
                {{ csrf_field() }}

                <div class="card">
                    <div class="card-header">
                        {{ __('Uw profiel wijzigen') }}
                    </div>

                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <th>{{ __('Contact Email') }}</th>
                            <td>
                                @php($contactEmail = old('contact-email', $customer->getContact()->getContactEmail()))
                                <div class="form-group">
                                    <input class="form-control {{ $contactEmail ? 'not-empty' : '' }}"
                                           value="{{ $contactEmail }}"
                                           name="contact-email"/>

                                    <label class="control-label">{{ __('Contact Email') }}</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Order Email') }}</th>
                            <td>
                                @php($orderEmail = old('order-email', $customer->getContact()->getOrderEmail()))
                                <div class="form-group">
                                    <input class="form-control {{ $orderEmail ? 'not-empty' : '' }}"
                                           value="{{ $orderEmail }}"
                                           name="order-email"/>

                                    <label class="control-label">{{ __('Order Email') }}</label>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="card-body">
                        <button type="submit" class="btn btn-primary pull-right">
                            {{ __('Opslaan') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
