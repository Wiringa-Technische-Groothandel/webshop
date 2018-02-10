@extends('admin.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="card card-2">
                    <h3>Content</h3>

                    <hr />

                    <form action="{{ route('admin.content::save_page') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="field-selector" class="col-sm-2 control-label">Velden: </label>

                            <div class="col-sm-10">
                                <select name="field" id="field-selector" class="form-control">
                                    <option value="---" selected>Selecteer een veld</option>
                                    @foreach($data as $field)
                                        <option value="{{ $field->name }}">{{ $field->page }} / {{ $field->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="step-2" style="display: none;">
                            <textarea name="content" id="page-editor" rows="30" class="form-control"></textarea>

                            <hr />

                            <button type="submit" class="btn btn-success btn-block">Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="card card-2">
                    <h3>Product omschrijving</h3>

                    <hr />

                    <form action="{{ route('admin.content::save_description') }}" method="POST" class="form-horizontal">

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="field" class="col-sm-2 control-label">Product nummer</label>

                            <div class="col-sm-10">
                                <input placeholder="Product nummer" id="product-number" type="number" name="product" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field" class="col-sm-2 control-label">Omschrijving</label>

                            <div class="col-sm-10">
                                <textarea name="content" id="description-editor" rows="30" class="form-control"></textarea>
                            </div>
                        </div>

                        <button class="btn btn-success" type="submit">Opslaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('document_end')
    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/ckeditor/adapters/jquery.js"></script>

    <script type="text/javascript">
        $fieldSelectorInput = $('#field-selector');
        $productNumberInput = $('#product-number');
        $descriptionEditor = $('#description-editor');
        $pageEditor = $('#page-editor');

        $fieldSelectorInput.change(function() {
            $.ajax({
                url: "{{ route('admin.content::content') }}",
                data: { page: this.value },
                dataType: 'json',
                success: function(data) {
                    var content = data.payload;

                    $pageEditor.val(content.content);
                    $('#step-2').show();
                }
            });
        });

        $productNumberInput.change(function () {
            if (this.value.length == 7) {
                $.ajax({
                    url: "{{ route('admin.content::description') }}",
                    data: { product: this.value },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        var content = data.payload;

                        $descriptionEditor.val(content.value);
                    }
                });
            }
        });

        $descriptionEditor.ckeditor();
        $pageEditor.ckeditor();
    </script>
@endsection