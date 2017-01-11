@extends('admin.master')

@section('document_start')
    @include('admin.carousel.components.modal')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="card card-2">
                    <button class="btn btn-success btn-block" data-toggle="modal" data-target="#addSlide">
                        <i class="fa fa-plus"></i> Slide toevoegen aan de carousel
                    </button>
                </div>
            </div>
        </div>

        @include('admin.carousel.components.cards')
    </div>
@endsection