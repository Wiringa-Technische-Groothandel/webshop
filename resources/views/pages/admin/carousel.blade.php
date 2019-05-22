@extends('layouts.admin')

@section('title', __('Carousel'))

@section('pre-content')
    @include('components.admin.carousel.addModal')
@endsection

@section('content')
    <button class="btn btn-success bmd-btn-fab" id="add-slide-button"
            data-toggle="modal" data-target="#addSlide" title="{{ __('Slide toevoegen aan de carousel') }}">
        <i class="fal fa-fw fa-plus"></i>
    </button>

    <div class="container-fluid">
        @include('components.admin.carousel.cards')
    </div>
@endsection

@push('scripts')
    <script>
        $('#add-slide-button').tooltip()
    </script>
@endpush

@push('styles')
    <style>
        .bmd-btn-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>
@endpush