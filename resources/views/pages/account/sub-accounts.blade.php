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
            @php
                $current = $account->getAttribute('username') === auth()->user()->getAttribute('username');
            @endphp

            <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <dl>
                            <dt>{{ __("Gebruikersnaam") }}</dt>
                            <dd>{{ $account->getUsername() }}</dd>

                            <dt>{{ __("E-Mail") }}</dt>
                            <dd>{{ $account->getContact()->getContactEmail() }}</dd>

                            <dt>{{ __("Rol") }}</dt>
                            <dd>
                                <div class="fa fa-spinner fa-spin" style="display: none;"></div>

                                <select class="form-control" data-user="{{ $account->getId() }}"
                                        autocomplete="off" {{ $current ? 'disabled' : '' }} onchange="updateRole(this)">
                                    @can('subaccounts-assign-admin')
                                        <option value="{{ \WTG\Models\Role::ROLE_ADMIN }}" {{ $account->getRole('role')->getLevel() === \WTG\Models\Role::ROLE_ADMIN ? 'selected' : '' }}>Admin</option>
                                    @endif

                                    @can('subaccounts-assign-manager')
                                        <option value="{{ \WTG\Models\Role::ROLE_MANAGER }}" {{ $account->getRole('role')->getLevel() === \WTG\Models\Role::ROLE_MANAGER ? 'selected' : '' }}>Manager</option>
                                    @endif

                                    <option value="{{ \WTG\Models\Role::ROLE_USER }}" {{ $account->getRole('role')->getLevel() === \WTG\Models\Role::ROLE_USER ? 'selected' : '' }}>Gebruiker</option>
                                </select>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    @include('components.account.sub-accounts.javascript')
@endpush