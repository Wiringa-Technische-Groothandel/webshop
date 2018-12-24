<form method="POST" class="form" action="{{ route('auth.password.resetPost') }}">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <input type="text" class="form-control" autocomplete="off" required name="company"
               value="{{ old('company') }}" placeholder="{{ __("Debiteurnummer") }}">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" autocomplete="off" required name="username"
               value="{{ old('username') }}" placeholder="{{ __("Gebruikersnaam") }}">
    </div>

    <div class="form-group">
        <input type="password" class="form-control" required name="password" placeholder="{{ __("Nieuw wachtwoord") }}">
    </div>

    <div class="form-group">
        <input type="password" class="form-control" required name="password_confirmation"
               placeholder="{{ __("Nieuw wachtwoord (verificatie)") }}">
    </div>

    <br />

    <button class="btn btn-outline-primary btn-lg btn-block" type="submit">{{ __('Wijzig wachtwoord') }}</button>
</form>