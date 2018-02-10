@extends('admin.master')

@section('document_start')
    @include('admin.user.components.modal')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="card card-2">
                    @include('admin.user.components.form')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('document_end')
    @include('admin.user.components.javascript')
@endsection