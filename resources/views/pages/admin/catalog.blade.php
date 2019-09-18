@extends('layouts.admin')

@section('title', __('Productbeheer'))

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-9">
                @include('components.admin.catalog.list')
            </div>

            <div class="col-sm-3">
                @include('components.admin.catalog.sync')

                @include('components.admin.catalog.index')
            </div>
        </div>
    </div>
@endsection