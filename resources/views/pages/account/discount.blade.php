@extends('layouts.account')

@section('title', __('Account / Kortingsbestand'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Kortingsbestand') }}
    </h2>
@endsection

@section('account.content')
    <div class="row">
        <div class="col-12 mb-3">
            <p>{{ __('Hier kunt u uw kortingsbestand ophalen in CSV of in ICC formaat. Dit bestand kan gedownload worden of naar uw contact e-mail adres (:email) gestuurd worden.', ['email' => $customer->getContact()->getContactEmail() ?: __('Geen contact e-mail adres ingesteld')]) }}</p>

            <form method="post">
                {{ csrf_field() }}

                <div class="row mb-3">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-control-label">
                                <b>{{ __('Bestandstype') }}</b>
                            </label>

                            <br>

                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-primary active">
                                    <input class="form-check-input" type="radio" name="format" id="icc_radio"
                                           value="{{ \WTG\Services\DiscountFileService::FORMAT_TYPE_ICC }}" checked>
                                    {{ __('ICC') }}
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input class="form-check-input" type="radio" name="format" id="csv_radio"
                                           value="{{ \WTG\Services\DiscountFileService::FORMAT_TYPE_CSV }}">
                                    {{ __('CSV') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-3">
                            <label class="form-control-label">
                                <b>{{ __('Ontvangstwijze') }}</b>
                            </label>

                            <br>

                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-primary active">
                                    <input class="form-check-input" type="radio" name="receive" id="download_radio"
                                           value="download" checked>
                                    {{ __('Download') }}
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input class="form-check-input" type="radio" name="receive" id="email_radio"
                                           value="email" {{ !$customer->getContact()->getContactEmail() ? 'disabled' : '' }}>
                                    {{ __('E-Mail') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-outline-primary" type="submit">{{ __('Verzenden') }}</button>
            </form>
        </div>
    </div>
@endsection