@extends('layouts.admin')

@section('title', __('Cache'))

@section('content')
    @if ($opcache_loaded)
        <button class="btn btn-danger bmd-btn-fab" onclick="resetCache()" data-toggle="tooltip" data-placement="top" title="{{ __('Cache legen') }}">
            <i class="fal fa-fw fa-trash"></i>
        </button>
    @endif

    <div class="container-fluid">
        @if (! $opcache_loaded)
            <div class="alert alert-danger">
                {{ __('De PHP OPCache module is niet geladen of niet geinstalleerd.') }}
            </div>
        @else
            <div class="row mb-3">
                <div class="col-6 col-md-4 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{ __('Geheugen gebruik in bytes') }}</h3>

                            <hr />

                            <div style="height: 200px;">
                                <canvas id="memoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{ __('Cache hitrate') }} {{ round(($opcache_stats->get('hits') / ($opcache_stats->get('hits') + $opcache_stats->get('misses'))) * 100, 1) }}%</h3>

                            <hr />

                            <div style="height: 200px;">
                                <canvas id="hitsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            @include('components.admin.cache.information')
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    @if ($opcache_loaded)
        @include('admin.cache.components.javascript')
    @endif
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