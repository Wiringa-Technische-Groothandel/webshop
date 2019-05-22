@extends('layouts.account')

@section('title', __('Account / Favorieten'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Favorieten') }}
    </h2>
@endsection

@section('account.content')
    @forelse($favorites as $group => $products)
        <h4>{{ $group }}</h4>

        <hr />

        @include('components.catalog.products', compact('products'))
    @empty
        <div class="alert alert-warning">
            {{ __('U hebt nog geen favorieten toegevoegd.') }}
        </div>
    @endforelse
@endsection
