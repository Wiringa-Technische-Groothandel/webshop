@extends('layouts.account')

@section('title', __('Account / Wachtwoord wijzigen'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Wachtwoord wijzigen') }}
    </h2>
@endsection

@section('account.content')
    <div class="row">
        <div class="col-12 col-sm-8 mx-auto mb-3">
            <form method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <input type="password" class="form-control" name="password_old" required>
                    <label for="password_old" class="control-label">{{ __("Huidig wachtwoord") }}</label>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" required>
                    <label for="password" class="control-label">{{ __("Nieuw wachtwoord") }}</label>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password_confirmation" required>
                    <label for="password_confirmation" class="control-label">
                        {{ __("Nieuw wachtwoord (bevestiging)") }}
                    </label>
                </div>

                <button type="submit" class="btn btn-primary pull-right">
                    {{ __('Wijzigen') }}
                </button>
            </form>
        </div>
    </div>
@endsection
