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
                            <th>Contact Email</th>
                            <td>
                                <input placeholder="{{ __('Contact Email') }}"
                                       class="form-control"
                                       value="{{ old('contact-email', $customer->getContact()->getContactEmail()) }}"
                                       name="contact-email"/>
                            </td>
                        </tr>
                        <tr>
                            <th>Order Email</th>
                            <td>
                                <input placeholder="{{ __('Order Email') }}"
                                       class="form-control"
                                       value="{{ old('order-email', $customer->getContact()->getOrderEmail()) }}"
                                       name="order-email"/>
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
