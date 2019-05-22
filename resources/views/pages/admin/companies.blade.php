@extends('layouts.admin')

@section('title', __('Klantbeheer'))

@section('pre-content')
    @include('components.admin.companies.addModal')
@endsection

@section('content')
    <button class="btn btn-success bmd-btn-fab" id="add-company" data-toggle="modal" data-target="#addCompany" title="{{ __('Debiteur toevoegen') }}">
        <i class="fal fa-fw fa-plus"></i>
    </button>

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-12">
                @include('components.admin.companies.list')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#add-company').tooltip();

        @if (old('name'))
            $('#addCompany').modal('show');
        @endif
    </script>
@endpush

@push('styles')
    <style>
        .bmd-btn-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
@endpush