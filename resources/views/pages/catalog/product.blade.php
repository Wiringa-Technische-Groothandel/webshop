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
                    <div class="col-12 col-sm-8 col-md-6 mb-3">
                        <a href="{{ $product->getImageUrl() }}" data-alt="{{ $product->getName() }}"
                           data-caption="{{ $product->getName() }}" data-lightbox="mobile-product-image"
                           class="d-block d-sm-none">
                            <img src="{{ $product->getImageUrl() }}" class="img-thumbnail w-25 float-right">
                        </a>

                        @include('components.catalog.product.price')
                    </div>
                </div>

                @if ($product->hasDescription())
                    @include('components.catalog.product.description')
                @endif

                @include('components.catalog.product.details')

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
    </div>
@endsection