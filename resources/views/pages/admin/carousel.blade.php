@extends('layouts.admin')

@section('title', 'Carousel')

@section('document_start')
    @include('components.admin.carousel.addModal')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-8 offset-sm-2">
                <div class="card card-2">
                    <button class="btn btn-success btn-block" data-toggle="modal" data-target="#addSlide">
                        <i class="fal fa-fw fa-plus"></i> {{ __('Slide toevoegen aan de carousel') }}
                    </button>
                </div>
            </div>
        </div>

        @include('components.admin.carousel.cards')
    </div>
@endsection