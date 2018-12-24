<form method="POST" class="form" action="{{ route('auth.password.email') }}">
    {{ csrf_field() }}

    <div class="form-group">
        <input type="text" class="form-control" autocomplete="off" required name="company"
               value="{{ old('company') }}" placeholder="{{ __("Debiteurnummer") }}">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" autocomplete="off" required name="username"
               value="{{ old('username') }}" placeholder="{{ __("Gebruikersnaam") }}">
    </div>

    <br />

    <button class="btn btn-outline-primary btn-lg btn-block" type="submit">{{ __('Wachtwoord resetten') }}</button>
</form>