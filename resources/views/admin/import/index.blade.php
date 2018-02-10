@extends('admin.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card card-2">
                    @include('admin.import.components.image')
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card card-2">
                    @include('admin.import.components.download')
                </div>
            </div>
        </div>

        {{--<div class="row">--}}
            {{--<div class="col-sm-6">--}}
                {{--<div class="card card-2">--}}
                    {{--@include('admin.import.components.discount')--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="col-sm-6">--}}
            {{--<div class="card card-2">--}}
            {{--@include('admin.import.components.product')--}}
            {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
@endsection