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

    <div class="d-none d-sm-block img-svg pipe-with-connector"></div>

    <hr class="d-block d-sm-none" />

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
                @block('about')
            </div>
        </div>
    </div>

    <div class="d-none d-sm-block img-svg pipe-with-connector rotate-180"></div>

    <hr class="d-block d-sm-none" />

    <h2 class="text-center block-title">{{ __('Vestiging') }}</h2>

    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31457.780959554224!2d6.559396656217749!3d53.224621828872294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c9d29bd38e3ecb%3A0x866182a966956b99!2sWiringa+Technische+Groothandel!5e0!3m2!1sen!2sdk!4v1534939333360"
            height="500" frameborder="0" style="border: 0; width: 100%; margin-bottom: -6px;" allowfullscreen></iframe>
@endsection
