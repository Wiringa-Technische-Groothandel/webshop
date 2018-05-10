<h3>
    <i class="fal fa-fw fa-image"></i> Afbeeldingen
</h3>

<hr />

<form action="{{ route('admin.import.image') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}

    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="imageFile" accept=".zip,image/*">
            <label class="custom-file-label" for="imageFile">{{ __('Kies een bestand') }}</label>
        </div>

        <small class="form-text text-muted">ZIP/Afbeelding, max. {{ ini_get('upload_max_filesize') }}</small>
    </div>

    <button type="submit" class="btn btn-success">Afbeeldingen uploaden</button>
</form>