@extends('layouts.main')

@section('title', __('Wachtwoord vergeten'))

@section('content')
    <h2 class="text-center block-title">{{ __('Wachtwoord vergeten') }}</h2>

    <hr>

    <div class="container">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="well mx-auto" style="max-width: 300px;">
                    @include('components.auth.forgot-password.form')
                </div>
            </div>
        </div>
    </div>
@endsection
