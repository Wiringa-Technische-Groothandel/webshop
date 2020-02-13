@extends('layouts.main')

@section('title', __(':product', [ 'product' => $product->getName() ]))

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-12 d-block d-md-none">
                <a href="{{ $previousUrl ?? route('catalog.assortment') }}" class="btn btn-link mb-3 px-0">
                    <i class="fal fa-fw fa-chevron-left"></i> {{ __('Terug naar overzicht') }}
                </a>
            </div>

            <div class="d-none d-sm-block col-4 mb-3" id="image">
                <div class="text-center">
                    <a href="{{ $previousUrl ?? route('catalog.assortment') }}" class="btn btn-link mb-3 d-none d-md-block">
                        <i class="fal fa-fw fa-chevron-left"></i> {{ __('Terug naar overzicht') }}
                    </a>

                    <a href="{{ $product->getImageUrl(\WTG\Catalog\Model\Product::IMAGE_SIZE_ORIGINAL) }}" data-alt="{{ $product->getName() }}"
                       data-caption="{{ $product->getName() }}" data-lightbox="desktop-product-image">
                        <img src="{{ $product->getImageUrl(\WTG\Catalog\Model\Product::IMAGE_SIZE_MEDIUM) }}" alt="{{ $product->getName() }}" class="img-thumbnail">
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-8">
                <h4 class="product-name">{{ $product->getName() }}</h4>

                <hr />

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <a href="{{ $product->getImageUrl(\WTG\Catalog\Model\Product::IMAGE_SIZE_LARGE) }}" data-alt="{{ $product->getName() }}"
                           data-caption="{{ $product->getName() }}" data-lightbox="mobile-product-image"
                           class="d-block d-sm-none">
                            <img src="{{ $product->getImageUrl(\WTG\Catalog\Model\Product::IMAGE_SIZE_MEDIUM) }}" class="img-thumbnail w-25 float-right">
                        </a>

                        @include('components.catalog.product.price')
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @if ($product->isPack())
                <div class="col-12 col-md-8 offset-md-4">
                    @include('components.catalog.product.pack')
                </div>
            @elseif ($product->isPackProduct())
                <div class="col-12 col-md-8 offset-md-4">
                    <div class="card border-warning mb-3">
                        <div class="card-header bg-warning">
                            <i class="fas fa-fw fa-exclamation-triangle"></i> {{ __('Dit product is onderdeel van een of meer actiepakketten.') }}
                        </div>

                        <div class="card-body">
                            <ul>
                                @foreach($product->getPackProducts() as $packProduct)
                                    <li>
                                        <a href="{{ $packProduct->getPack()->getProduct()->getUrl() }}">
                                            {{ $packProduct->getPack()->getProduct()->getName() }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-12 col-md-8 offset-md-4">
                @if ($product->getDescription())
                    @include('components.catalog.product.description')
                @endif

                @include('components.catalog.product.details')
            </div>
        </div>
    </div>
@endsection
