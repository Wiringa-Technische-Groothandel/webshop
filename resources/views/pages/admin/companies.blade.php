@extends('layouts.admin')

@section('title', __('Klantbeheer'))

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-12">
                @include('components.admin.companies.list')
            </div>
        </div>
    </div>
@endsection