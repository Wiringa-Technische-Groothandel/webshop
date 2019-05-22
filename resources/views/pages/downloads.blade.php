@extends('layouts.main')

@section('title', __('Downloads'))

@section('content')
    <h2 class="text-center block-title" id="catalog">Catalogus</h2>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @block('downloads.catalog')
            </div>
        </div>
    </div>

    <hr />

    <h2 class="text-center block-title" id="flyers">Flyers</h2>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @block('downloads.flyers')
            </div>
        </div>
    </div>

    <hr />

    <h2 class="text-center block-title" id="contact">Artikelbestanden</h2>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @block('downloads.products')
            </div>
        </div>
    </div>

    <div class="separator-half"></div>
@endsection
