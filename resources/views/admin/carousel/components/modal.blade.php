<div class="modal fade" id="addSlide" tabindex="-1" role="dialog" aria-labelledby="addSlideLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                <h4 class="modal-title" id="addSlideLabel">Slide toevoegen</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.carousel::create') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">Afbeelding</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Bladeren&hellip; <input type="file" name="image" required>
                                        </span>
                                    </span>
                                <input type="text" class="form-control" readonly id="fileName">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Titel</label>
                        <div class="col-sm-10">
                            <input value="{{ old('title') }}" class="form-control" placeholder="Titel" type="text" name="title" maxlength="100" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="caption" class="col-sm-2 control-label">Omschrijving</label>
                        <div class="col-sm-10">
                            <input value="{{ old('caption') }}" class="form-control" placeholder="Omschrijving" type="text" name="caption" maxlength="200" required>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                    <button type="submit" class="btn btn-primary">Toevoegen</button>
                </form>
            </div>
        </div>
    </div>
</div>