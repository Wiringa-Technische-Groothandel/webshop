<div class="modal fade" tabindex="-1" id="addSlide" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" class="form form-horizontal">
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
                        <label for="imageFile">{{ __('Afbeelding') }}</label>

                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="imageFile" accept="image/*">
                            <label class="custom-file-label" for="imageFile">{{ __('Kies een bestand') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">{{ __('Titel') }}</label>
                        <input value="{{ old('title') }}" class="form-control" placeholder="{{ __('Titel') }}" type="text"
                               name="title" maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label for="caption">{{ __('Omschrijving') }}</label>
                        <input value="{{ old('caption') }}" class="form-control" placeholder="{{ __('Omschrijving') }}"
                               type="text" name="caption" maxlength="200" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-primary">{{ __('Toevoegen') }}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Sluiten') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>