<form method="POST" action="{{ route('auth.login', [ 'toUrl' => url()->current() ]) }}" class="form form-horizontal">
    {{ csrf_field() }}

    <div class="form-group row">
        <label for="username" class="col-sm-4 col-form-label">{{ __("Debiteurnummer") }}</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" autocomplete="off" required name="company"
                   value="{{ old('company') }}" placeholder="{{ __("Debiteurnummer") }}">
            <small class="form-text text-muted">{{ __("Bijv. 12345") }}</small>
        </div>
    </div>

    <div class="form-group row">
        <label for="username" class="col-sm-4 col-form-label">{{ __("Gebruikersnaam") }}</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" autocomplete="off" required name="username"
                   value="{{ old('username') }}" placeholder="{{ __("Gebruikersnaam") }}">
            <small class="form-text text-muted">{{ __("Bijv. 'piet'") }}</small>
        </div>
    </div>

    <div class="form-group row">
        <label for="username" class="col-sm-4 col-form-label">{{ __("Wachtwoord") }}</label>
        <div class="col-sm-8">
            <input type="password" class="form-control" autocomplete="off" required name="password"
                   placeholder="{{ __("Wachtwoord") }}">
            <small class="form-text">
{{--                <a href="{{ route('auth.password.email') }}">{{ __("Wachtwoord vergeten?") }}</a>--}}
            </small>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-8 ml-auto">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="remember">
                    {{ __("Ingelogd blijven?") }}
                </label>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-8 ml-auto">
            <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
        </div>
    </div>
</form>