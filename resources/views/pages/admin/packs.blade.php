@extends('layouts.admin')

@section('title', __('Actiepakketten'))

@section('pre-content')
    @include('components.admin.packs.addModal')
@endsection

@section('content')
    <button class="btn btn-success bmd-btn-fab" data-toggle="modal" data-target="#addProductPack" title="{{ __('Actiepakket toevoegen') }}">
        <i class="fal fa-fw fa-plus"></i>
    </button>

    <div class="container-fluid">
        <div class="row mb-3">
            @forelse($packs as $pack)
                <div class="col-sm-6 col-md-4">
                    @include('components.admin.packs.pack')
                </div>

                @if ($loop->iteration % 3 === 0)
                    <div class="clearfix visible-md visible-lg"></div>
                    <br class="visible-md visible-lg" />
                @endif

                @if ($loop->iteration % 2 === 0)
                    <div class="clearfix visible-xs visible-sm"></div>
                    <br class="visible-sm" />
                @endif

                <br class="visible-xs" />
            @empty
                <div class="col-sm-6 offset-sm-3">
                    <div class="alert alert-info">
                        {{ __('Geen actiepakketten gevonden.') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bmd-btn-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>
@endpush
