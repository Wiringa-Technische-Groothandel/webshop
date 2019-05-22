@extends('layouts.main')

@section('content')
    @yield('account.title')

    <hr />

    <div class="container mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                @include('components.navigation.account')
            </div>

            <div class="col-12 col-md-8 col-lg-9">
                @yield('account.content')
            </div>
        </div>
    </div>
@endsection