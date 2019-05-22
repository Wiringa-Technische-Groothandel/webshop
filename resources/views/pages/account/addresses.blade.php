@extends('layouts.account')

@section('title', __('Account / Adressen'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Adressen') }}
    </h2>
@endsection

@section('account.content')
    @include('components.account.addresses.addModal')

    <div class="row">
        <div class="col-12">
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#address-modal">
                {{ __("Adres toevoegen") }}
            </button>
        </div>
    </div>

    <address-list :addresses="{{ $addresses }}" update-url="{{ route('account.addresses') }}"
                  default-address="{{ $defaultAddress }}"></address-list>
@endsection