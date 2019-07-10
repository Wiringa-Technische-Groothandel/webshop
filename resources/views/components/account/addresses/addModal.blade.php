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
                        <input type="text" class="form-control" 
                               value="{{ old('name') }}" name="name" required>
                        <label class="control-label">{{ __("Naam") }}*</label>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" 
                               value="{{ old('address') }}" name="address" required>
                        <label class="control-label">{{ __("Adres") }}*</label>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" maxlength="7"
                                   value="{{ old('postcode') }}" name="postcode" required>
                            <label class="control-label">{{ __("Postcode") }}*</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" 
                                   value="{{ old('city') }}" name="city" required>
                            <label class="control-label">{{ __("Plaats") }}*</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="tel" class="form-control" 
                                   value="{{ old('phone') }}" name="phone">
                            <label class="control-label">{{ __("Telefoon") }}</label>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="tel" class="form-control" 
                                   value="{{ old('mobile') }}" name="mobile">
                            <label class="control-label">{{ __("Mobiel") }}</label>
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