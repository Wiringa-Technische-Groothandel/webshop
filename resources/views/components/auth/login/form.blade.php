<form method="POST" action="{{ route('auth.login', [ 'toUrl' => url()->current() ]) }}" class="form">
    {{ csrf_field() }}

    <div class="form-group">
        <input type="text" class="form-control" autocomplete="off" required name="company"
               value="{{ old('company') }}">
        <label class="control-label">{{ __("Debiteurnummer") }}</label>
    </div>

    <div class="form-group">
        <input type="text" class="form-control" autocomplete="off" required name="username"
               value="{{ old('username') }}">
        <label class="control-label">{{ __("Gebruikersnaam") }}</label>
    </div>

    <div class="form-group">
        <input type="password" class="form-control" autocomplete="off" required name="password">
        <label class="control-label">{{ __("Wachtwoord") }}</label>
        <small class="form-text">
            <a href="{{ route('auth.password.request') }}">{{ __("Wachtwoord vergeten?") }}</a>
        </small>
    </div>

    <div class="custom-control custom-checkbox mb-3">
        <input type="checkbox" class="custom-control-input" id="remember-me" name="remember">
        <label class="custom-control-label" for="remember-me">{{ __("Ingelogd blijven?") }}</label>
    </div>

    <button type="submit" class="btn btn-outline-success btn-lg btn-block">{{ __('Login') }}</button>

    <div class="mt-3 text-center">
        <a href="{{ route('auth.register') }}">
            {{ __('Nog geen account? Registreer hier!') }}
        </a>
    </div>
</form>