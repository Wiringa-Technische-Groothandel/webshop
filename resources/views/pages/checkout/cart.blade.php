@extends('layouts.main')

@section('title', __('Bestelling / Winkelwagen'))

@section('content')
    <h2 class="text-center block-title">
        {{ __('Winkelwagen') }}
    </h2>

    <div class="container">
        @if ($cart->count() > 0)
            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 33%; height: 20px">
                    {{ __('Overzicht') }}
                </div>
                <div class="progress-bar bg-info" role="progressbar" style="width: 34%; height: 20px">
                    {{ __('Adres') }}
                </div>
                <div class="progress-bar bg-info" role="progressbar" style="width: 33%; height: 20px">
                    {{ __('Afronden') }}
                </div>
            </div>

            <hr />
        @endif

        @if ($cart->count() > 0)
            <cart items-url="{{ route('checkout.cart.items') }}"
                  continue-url="{{ session('continue.url') }}"></cart>
        @else
            <div class="col-12 col-sm-8 mx-auto">
                <div class="alert alert-warning text-center">
                    {{ __('U hebt nog geen producten in uw winkelwagen.') }}
                </div>
            </div>
        @endif
    </div>
@endsection