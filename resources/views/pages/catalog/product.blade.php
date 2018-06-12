@extends('layouts.main')

@section('title', __('Product - :product', [ 'product' => $product->getSku() ]))

@section('content')
    <hr />

    <div class="container">
        <div class="row mb-3">
            <div class="d-none d-sm-block col-4 mb-3" id="image">
                <div class="text-center">
                    <a href="{{ $previousUrl ?? route('catalog.assortment') }}" class="btn btn-link mb-3">
                        <i class="fal fa-fw fa-chevron-left"></i> {{ __('Terug naar overzicht') }}
                    </a>

                    <a href="{{ $product->getImageUrl() }}" data-alt="{{ $product->getName() }}"
                       data-caption="{{ $product->getName() }}" data-lightbox="desktop-product-image">
                        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->getName() }}" class="img-thumbnail">
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-8">
                <h4 class="product-name">{{ $product->getName() }}</h4>

                <hr />

                <div class="row">
                    <div class="col-12 col-sm-8 col-md-6">
                        <a href="{{ $product->getImageUrl() }}" data-alt="{{ $product->getName() }}"
                           data-caption="{{ $product->getName() }}" data-lightbox="mobile-product-image"
                           class="d-block d-sm-none">
                            <img src="{{ $product->getImageUrl() }}" class="img-thumbnail w-25 float-right">
                        </a>

                        @auth
                            <price :product="{{ $product }}"></price>

                            <br />

                            <div class="row">
                                <div class="col-12 col-md-10 mb-3">
                                    <add-to-cart sku="{{ $product->getSku() }}"
                                                 sales-unit-single="{{ ucfirst(unit_to_str($product->getSalesUnit(), false)) }}"
                                                 sales-unit-plural="{{ ucfirst(unit_to_str($product->getSalesUnit())) }}"
                                                 submit-url="{{ route('checkout.cart') }}"></add-to-cart>
                                </div>

                                <div class="col-12 col-md-2">
                                    <favorites-toggle-button sku="{{ $product->getSku() }}"
                                                      check-url="{{ route('favorites.check') }}"
                                                      toggle-url="{{ route('favorites.toggle') }}"></favorites-toggle-button>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-12 col-md-8">

                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                {{--@if (count($pack_list) >= 1)--}}
                {{--<div class="alert alert-warning text-center">--}}
                {{--<h3>Attentie!</h3>--}}
                {{--<p>--}}
                {{--Dit product is onderdeel van 1 of meer actiepakketten: <br />--}}
                {{--@foreach($pack_list as $pack)--}}
                {{--<a href="/product/{{ $pack->product_number }}">{{ $pack->product->name }}</a><br />--}}
                {{--@endforeach--}}
                {{--</p>--}}
                {{--</div>--}}
                {{--@endif--}}
            </div>
        </div>

        @if ($product->hasDescription())
            <div class="row mb-3">
                <div class="col-12 col-md-8 offset-md-4">
                    <div class="card">
                        <div class="card-header">
                            <b>{{ __('Omschrijving') }}</b>
                        </div>
                        <div class="card-body">
                            <div class="card-text">
                                {!! $product->getDescription()->getValue() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-12 col-md-8 offset-md-4">
                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Details') }}</b>
                    </div>

                    <table class="table">
                        <tr>
                            <td><b>{{ __('Product nummer') }}</b></td>
                            <td>{{ $product->getSku() }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('Product groep') }}</b></td>
                            <td>{{ $product->getGroup() }}</td>
                        </tr>
                        {{--<tr>--}}
                        {{--<td><b>Fabrieksnummer</b></td>--}}
                        {{--<td>{{ $product->getAlternateSku() }}</td>--}}
                        {{--</tr>--}}
                        @if ($product->getEan())
                            <tr>
                                <td><b>{{ __('EAN') }}</b></td>
                                <td>{{ $product->getEan() }}</td>
                            </tr>
                        @endif
                        {{--<tr>--}}
                        {{--<td><b>Voorraad</b></td>--}}
                        {{--<td></td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td><b>{{ __('Merk') }}</b></td>
                            <td>{{ $product->getBrand() }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('Serie') }}</b></td>
                            <td>{{ $product->getSeries() }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('Type') }}</b></td>
                            <td>{{ $product->getType() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection