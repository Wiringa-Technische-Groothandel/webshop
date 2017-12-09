@extends('layouts.account')

@section('title', __('Account / Wachtwoord wijzigen'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ trans('titles.account.change-password') }}
    </h2>
@endsection

@section('account.content')
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <form method="POST">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <div class="form-group">
                    <label for="password_old" class="control-label">{{ __("Huidig wachtwoord") }}</label>

                    <input type="password" class="form-control" name="password_old"
                           placeholder="{{ __("Huidig wachtwoord") }}" required>
                </div>

                <div class="form-group">
                    <label for="password" class="control-label">{{ __("Nieuw wachtwoord") }}</label>

                    <input type="password" class="form-control" name="password"
                           placeholder="{{ __("Nieuw wachtwoord") }}" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="control-label">
                        {{ __("Nieuw wachtwoord (bevestiging)") }}
                    </label>

                    <input type="password" class="form-control" name="password_confirmation"
                           placeholder="{{ __("Nieuw wachtwoord (bevestiging)") }}" required>
                </div>

                <button type="submit" class="btn btn-primary pull-right">Wijzigen</button>
            </form>
        </div>
    </div>
@endsection
