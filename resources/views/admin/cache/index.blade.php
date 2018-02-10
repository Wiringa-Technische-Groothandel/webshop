@extends('admin.master')

@section('document_start')
    @include('admin.cache.components.modal')
@endsection

@section('content')
    <div class="container-fluid">
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
    </div>
@endsection

@section('document_end')
    @include('admin.cache.components.javascript')
@endsection