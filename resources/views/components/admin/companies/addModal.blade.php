<div class="modal fade" tabindex="-1" id="addCompany" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" class="form">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Slide toevoegen') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="bmd-label-floating">{{ __('Naam') }}</label>
                        <input value="{{ old('name') }}" class="form-control" name="name" type="text" required>
                    </div>

                    <div class="form-group">
                        <label class="bmd-label-floating">{{ __('Debiteurnummer') }}</label>
                        <input value="{{ old('customer-number') }}" type="text" name="customer-number"
                               class="form-control" maxlength="5" required>
                    </div>

                    <div class="form-group">
                        <label class="bmd-label-floating">{{ __('Street') }}</label>
                        <input value="{{ old('street') }}" class="form-control" type="text" name="street" required>
                    </div>

                    <div class="form-group">
                        <label class="bmd-label-floating">{{ __('Postcode') }}</label>
                        <input value="{{ old('postcode') }}" maxlength="7" class="form-control" type="text"
                               name="postcode" required>
                    </div>

                    <div class="form-group">
                        <label class="bmd-label-floating">{{ __('Plaats') }}</label>
                        <input value="{{ old('city') }}" class="form-control" type="text" name="city" required>
                    </div>

                    <div class="checkbox mt-3">
                        <label>
                            <input type="checkbox" name="active" {{ old('active') ? 'checked' : '' }}> {{ __('Is actief') }}
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-primary mr-2">{{ __('Toevoegen') }}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Sluiten') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>