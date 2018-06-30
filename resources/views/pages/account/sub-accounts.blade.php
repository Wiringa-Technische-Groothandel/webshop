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
                            <dd>{{ $account->getAttribute('username') }}</dd>

                            <dt>{{ __("E-Mail") }}</dt>
                            <dd>{{ $account->contact->getAttribute('contact_email') }}</dd>

                            <dt>{{ __("Rol") }}</dt>
                            <dd>
                                <div class="fa fa-spinner fa-spin" style="display: none;"></div>

                                <select class="form-control" data-user="{{ $account->getAttribute('id') }}"
                                        autocomplete="off" {{ $current ? 'disabled' : '' }} onchange="updateRole(this)">
                                    @if (auth()->user()->can('assign admin role'))
                                        <option value="admin" {{ $account->getAttribute('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    @endif

                                    @if (auth()->user()->can('assign manager role'))
                                        <option value="manager" {{ $account->getAttribute('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                    @endif

                                    <option value="user" {{ $account->getAttribute('role') === 'user' ? 'selected' : '' }}>Gebruiker</option>
                                </select>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            {{--<div class="row sub-account {{ $current ? 'current-account' : '' }}">--}}
            {{--<div class="col-sm-4 sub-account-username">--}}

            {{--</div>--}}
            {{--<div class="col-sm-4 sub-account-email">--}}

            {{--</div>--}}
            {{--<div class="col-sm-2 sub-account-role">--}}

            {{--</div>--}}
            {{--<div class="col-sm-2 sub-account-actions">--}}
            {{--@if (!$current)--}}
            {{--<a href="#" title="Verstuur 'wachtwoord vergeten' mail naar {{ $account->getAttribute('email') }}"--}}
            {{--class="btn btn-link" data-email="{{ $account->getAttribute('email') }}"><i class="fa fa-fw fa-key"></i>--}}
            {{--</a>--}}

            {{--<a href="#" data-username="{{ $account->getAttribute('username') }}"--}}
            {{--class="btn btn-link icon-danger edit-user"><i class="fa fa-fw fa-trash-o"></i>--}}
            {{--</a>--}}
            {{--@endif--}}
            {{--</div>--}}
            {{--</div>--}}
        @endforeach
    </div>
@endsection

@section('extraJS')
    @include('components.account.sub-accounts.javascript')
@endsection