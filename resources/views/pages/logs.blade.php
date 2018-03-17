@extends('layouts.main')

@section('title', 'Logs')

@section('content')
    <h2 class="text-center block-title" id="catalog">
        {{ __('Logs') }}
    </h2>

    <hr />

    <logs :items="{{ $logs }}"></logs>
@endsection