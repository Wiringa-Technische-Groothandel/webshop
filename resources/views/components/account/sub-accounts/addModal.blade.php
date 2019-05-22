<div class="modal fade" id="addAccountDialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Account toevoegen") }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">{{ __("Gebruikersnaam") }}*</label>
                        <input type="text" class="form-control" placeholder="{{ __("Gebruikersnaam") }}"
                               value="{{ old('username') }}" name="username" required>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">{{ __("E-Mail") }}*</label>
                        <input type="email" class="form-control" placeholder="{{ __("E-Mail") }}"
                               value="{{ old('email') }}" name="email" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{ __("Wachtwoord") }}*</label>
                            <input type="password" class="form-control" placeholder="{{ __("Wachtwoord") }}"
                                   name="password" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{ __("Wachtwoord (verificatie)") }}*</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                   placeholder="{{ __("Wachtwoord (verificatie)") }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">{{ __("Rol") }}*</label>

                        <select name="role" class="form-control" autocomplete="off">
                            <option value="">{{ __("--- Selecteer een rol ---") }}</option>

                            @can('subaccounts-assign-manager')
                                <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>
                                    {{ __("Manager") }}
                                </option>
                            @endcan

                            <option value="user" {{  old('role') === 'user' ? 'selected' : '' }}>
                                {{ __("Gebruiker") }}
                            </option>
                        </select>
                    </div>

                    <small class="form-text text-muted">
                        {{ __("Velden gemarkeerd met een * zijn verplicht.") }}
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Sluiten") }}</button>
                    <button type="submit" class="btn btn-primary">{{ __("Toevoegen") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>