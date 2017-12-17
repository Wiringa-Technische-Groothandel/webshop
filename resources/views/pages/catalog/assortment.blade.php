@extends('layouts.main')

@section('title', __('Assortiment'))

@section('content')
    <h2 class="text-center block-title">{{ __('Assortiment') }}</h2>

    <hr />

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form action="{{ route('catalog.search') }}">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control" placeholder="{{ __('Zoeken') }}" value="{{ request('query') }}">
                        <span class="input-group-btn"><button class="btn btn-primary" type="button"><i class="fal fa-fw fa-search"></i></button></span>
                    </div>

                    <hr />

                    @include('components.catalog.filters')
                </form>
            </div>

            <div class="col-md-9">
                @include('components.catalog.products')

                <div class="my-3">
                    {{ $results->get('products')->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
