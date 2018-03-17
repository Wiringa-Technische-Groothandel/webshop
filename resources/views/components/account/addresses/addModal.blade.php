<div class="modal fade" id="address-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Adres toevoegen") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">{{ __("Naam") }}*</label>
                        <input type="text" class="form-control" placeholder="{{ __("Naam") }}"
                               value="{{ old('name') }}" name="name" required>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">{{ __("Adres") }}*</label>
                        <input type="text" class="form-control" placeholder="{{ __("Straat + Huisnummer") }}"
                               value="{{ old('address') }}" name="address" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{ __("Postcode") }}*</label>
                            <input type="text" class="form-control" placeholder="{{ __("Postcode") }}" maxlength="7"
                                   value="{{ old('postcode') }}" name="postcode" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{ __("Plaats") }}*</label>
                            <input type="text" class="form-control" placeholder="{{ __("Plaats") }}"
                                   value="{{ old('city') }}" name="city" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{ __("Telefoon") }}</label>
                            <input type="tel" class="form-control" placeholder="{{ __("Telefoon") }}"
                                   value="{{ old('phone') }}" name="phone">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">{{ __("Mobiel") }}</label>
                            <input type="tel" class="form-control" placeholder="{{ __("Mobiel") }}"
                                   value="{{ old('mobile') }}" name="mobile">
                        </div>
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