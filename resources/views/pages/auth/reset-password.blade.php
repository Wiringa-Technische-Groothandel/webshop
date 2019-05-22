@extends('layouts.main')

@section('title', __('Reset wachtwoord'))

@section('content')
    <h2 class="text-center block-title">{{ __('Reset wachtwoord') }}</h2>

    <hr>

    <div class="container">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="well mx-auto" style="max-width: 300px;">
                    @include('components.auth.reset-password.form')
                </div>
            </div>
        </div>
    </div>
@endsection