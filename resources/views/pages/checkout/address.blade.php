@extends('layouts.main')

@section('title', __('Bestelling / Afleveradres'))

@section('content')
    @include('components.checkout.address.changeModal')

    <h2 class="text-center block-title">
        {{ __("Winkelwagen") }}
    </h2>

    <div class="container">
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 33%; height: 20px">
                {{ __("Overzicht") }}
            </div>

            <div class="progress-bar bg-primary" role="progressbar" style="width: 34%; height: 20px">
                {{ __("Adres") }}
            </div>

            <div class="progress-bar bg-info" role="progressbar" style="width: 33%; height: 20px">
                {{ __("Afronden") }}
            </div>
        </div>

        <hr />

        <form method="post" action="{{ routeIf('checkout.finish') }}">
            {{ csrf_field() }}

            <div id="addresses-container">
                <div class="row mb-3">
                    <div class="col-12 col-sm-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                {{ __('Afleveradres') }}
                            </div>
                            <div class="card-body">
                                <cart-address :address="{{ $quoteAddress ?: 0 }}"></cart-address>

                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#change-address-modal">
                                    {{ __('Adres wijzigen') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                {{ __('Opmerking') }}
                            </div>
                            <div class="card-body">
                                <textarea title="{{ __('Opmerking') }}" name="comment" style="min-height: 126px;"
                                          class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 mt-sm-5">
                    <div class="col-12 col-sm-6 order-2 order-sm-1 mb-3">
                        <a class="btn btn-outline-info d-inline-block" href="{{ routeIf('checkout.cart') }}">
                            <i class="fal fa-fw fa-chevron-left"></i> {{ __('Vorige stap') }}
                        </a>
                    </div>

                    @if ($quoteAddress)
                        <div class="col-12 col-sm-6 mr-auto order-1 order-sm-2 mb-3 text-right">
                            <button class="btn btn-outline-success d-block d-sm-inline" type="submit">
                                {{ __('Bestelling afronden') }} <i class="fal fa-fw fa-check"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@section('extraJS')
    <script>
        var $deliveryAddress = $("#delivery-address");
        var $changeAddressModal = $('#change-address-modal');

        $('.change-address-button').on('click', function () {
            var $this = $(this);
            var $selectedAddress = $($this.data('target'));

            $deliveryAddress.html($selectedAddress.html());
            $changeAddressModal.modal('hide');

            axios.patch(window.location.href, {
                addressId: $this.data('address-id')
            })
                .catch(function (response) {
                    alert(response);
                });
        });
    </script>
@endsection