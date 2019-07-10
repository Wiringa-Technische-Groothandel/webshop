<form method="POST" class="form" action="{{ route('auth.password.resetPost') }}" autocomplete="off">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

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
        <input type="password" class="form-control" required name="password">
        <label class="control-label">{{ __("Nieuw wachtwoord") }}</label>
    </div>

    <div class="form-group">
        <input type="password" class="form-control" required name="password_confirmation">
        <label class="control-label">{{ __("Nieuw wachtwoord (verificatie)") }}</label>
    </div>

    <br />

    <button class="btn btn-outline-primary btn-lg btn-block" type="submit">{{ __('Wijzig wachtwoord') }}</button>
</form>