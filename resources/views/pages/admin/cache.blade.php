@extends('layouts.admin')

@section('title', 'Cache')

@section('document_start')
    @include('admin.cache.components.modal')
@endsection

@section('content')
    <div class="container-fluid">
        @if (! $opcache_loaded)
            <div class="alert alert-danger">
                {{ __('De PHP OPCache module is niet geladen of niet geinstalleerd.') }}
            </div>
        @else
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="card card-2">
                        <button data-target="#resetCacheModal" data-toggle="modal" class="btn btn-danger btn-block">Reset cache</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-md-4 col-md-offset-2">
                    <div class="card card-2">
                        <h3>Geheugen gebruik</h3>

                        <hr />

                        <div style="height: 200px;">
                            <canvas id="memoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 col-md-4">
                    <div class="card card-2">
                        <h3>Cache hitrate {{ round(($opcache_stats->get('hits') / ($opcache_stats->get('hits') + $opcache_stats->get('misses'))) * 100, 1) }}%</h3>

                        <hr />

                        <div style="height: 200px;">
                            <canvas id="hitsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="card card-2">
                        @include('admin.cache.components.information')
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('document_end')
    @if ($opcache_loaded)
        @include('admin.cache.components.javascript')
    @endif
@endsection