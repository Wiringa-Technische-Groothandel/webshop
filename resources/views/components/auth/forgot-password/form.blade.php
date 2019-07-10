<form method="POST" class="form" action="{{ route('auth.password.email') }}">
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

    <br />

    <button class="btn btn-outline-primary btn-lg btn-block" type="submit">{{ __('Wachtwoord resetten') }}</button>
</form>