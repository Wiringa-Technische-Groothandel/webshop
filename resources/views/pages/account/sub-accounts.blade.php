@extends('layouts.account')

@section('title', __('Account / Sub-Accounts'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Sub-Accounts') }}
    </h2>
@endsection

@section('before_content')
    <!-- Add user modal -->
    @include('components.account.sub-accounts.addModal')

    <!-- Delete user modal -->
    @include('components.account.sub-accounts.deleteModal')
@endsection

@section('account.content')
    <div class="row">
        <div class="col-12 mb-3">
            <button data-target="#addAccountDialog" data-toggle="modal" class="btn btn-success">
                {{ __("Nieuw account aanmaken") }}
            </button>
        </div>
    </div>

    <div class="row">
        @foreach($accounts as $account)
            <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">
                <sub-account
                        :can-edit="{{ (int) ($account->getAttribute('username') === auth()->user()->getAttribute('username')) }}"
                        :account="{{ $account }}"
                        :current-role="{{ $account->getRole()->getLevel() }}"
                        :can-assign-admin="{{ (int) Gate::allows('subaccounts-assign-admin') }}"
                        :can-assign-manager="{{ (int) Gate::allows('subaccounts-assign-manager') }}"
                        :role-admin="{{ \WTG\Models\Role::ROLE_ADMIN }}"
                        :role-manager="{{ \WTG\Models\Role::ROLE_MANAGER }}"
                        :role-user="{{ \WTG\Models\Role::ROLE_USER }}"
                ></sub-account>
            </div>
        @endforeach
    </div>
@endsection
