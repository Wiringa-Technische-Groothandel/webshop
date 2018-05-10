@extends('layouts.admin')

@section('title', __('E-Mail'))

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-8">
                {{--@include('components.admin.email.stats')--}}
            </div>
            <div class="col-sm-4">
                @include('components.admin.email.test-email')
            </div>
        </div>
    </div>
@endsection
