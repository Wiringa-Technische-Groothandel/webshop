@extends('layouts.home')

@section('title', __('Voorpagina'))

@section('content')
    <h2 class="text-center block-title" id="news">{{ __('Nieuws') }}</h2>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @block('news')
            </div>
        </div>
    </div>

    <div class="img-svg pipe-with-connector"></div>

    <h2 class="text-center block-title" id="contact">{{ __('Contact') }}</h2>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('components.contact')
            </div>
        </div>
    </div>

    <div class="img-png cover-image company-image"></div>

    <h2 class="text-center block-title" id="about">{{ __('Over ons') }}</h2>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{--@block('about')--}}
            </div>
        </div>
    </div>

    <div class="img-svg pipe-with-connector rotate-180"></div>

    <h2 class="text-center block-title">{{ __('Vestiging') }}</h2>

    <googlemaps-map id="location-map" style="height: 500px;" :center="{ lat: 53.235926, lng: 6.590660 }" :zoom="14"
                    :options="{ gestureHandling: 'none' }">

        <googlemaps-marker :position="{ lat: 53.235926, lng: 6.590660 }"></googlemaps-marker>
    </googlemaps-map>
@endsection
