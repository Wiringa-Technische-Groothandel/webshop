@extends('layouts.main')

@section('title', __('Login'))

@section('content')
    <h2 class="text-center block-title" id="login">Login</h2>

    <hr />

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto mb-3">
                @include('components.auth.login.form')
            </div>
        </div>
    </div>
@endsection