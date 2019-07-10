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
                        <input type="text" class="form-control" autocomplete="username"
                               value="{{ old('username') }}" name="username" required>
                        <label class="control-label">{{ __("Gebruikersnaam") }}*</label>
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" autocomplete="email"
                               value="{{ old('email') }}" name="email" required>
                        <label class="control-label">{{ __("E-Mail") }}*</label>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="password" class="form-control" autocomplete="new-password"
                                   name="password" required>
                            <label class="control-label">{{ __("Wachtwoord") }}*</label>
                        </div>

                        <div class="form-group col-md-6">
                            <input type="password" class="form-control" autocomplete="new-password"
                                   name="password_confirmation"required>
                            <label class="control-label">{{ __("Wachtwoord (verificatie)") }}*</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <select name="role" class="form-control fixed" autocomplete="off">
                            <option value="">{{ __("--- Selecteer een rol ---") }}</option>

                            @can('subaccounts-assign-manager')
                                <option value="{{ \WTG\Models\Role::ROLE_MANAGER }}" {{ old('role') === 'manager' ? 'selected' : '' }}>
                                    {{ __("Manager") }}
                                </option>
                            @endcan

                            <option value="{{ \WTG\Models\Role::ROLE_USER }}" {{  old('role') === 'user' ? 'selected' : '' }}>
                                {{ __("Gebruiker") }}
                            </option>
                        </select>

                        <label class="control-label">{{ __("Rol") }}*</label>
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