@extends('admin.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="card card-2">
                    <h3>Catalogus</h3>

                    <hr />

                    <form action="{{ route('admin.export::catalog') }}" method="POST" class="form form-horizontal">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="footer" class="col-sm-2 control-label">Pagina footer</label>
                            <div class="col-sm-10">
                                <input class="form-control" placeholder="Pagina footer" type="text" value="{{ $currentFooter }}" name="footer" maxlength="300">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Genereren</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="card card-2">
                    <h3>Prijslijst</h3>

                    <hr />

                    <form action="{{ route('admin.export::pricelist') }}" method="POST" class="form form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="file" class="col-sm-2 control-label">Bestand debiteur</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Bladeren&hellip; <input type="file" name="file" required>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly id="fileName">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="user_id" class="col-sm-2 control-label">Debiteur nummer</label>
                            <div class="col-sm-10">
                                <input class="form-control" placeholder="Debiteur nummer" type="text" name="user_id" value="{{ old('user_id') }}" maxlength="10" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="position" class="col-sm-2 control-label">Positie productnummer</label>
                            <div class="col-sm-10">
                                <input class="form-control" placeholder="Positie productnummer" type="number" name="position" value="{{ old('position') }}" maxlength="1" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="position" class="col-sm-2 control-label">Separator</label>
                            <div class="col-sm-10">
                                <input class="form-control" placeholder="Separator" type="text" name="separator" value="{{ (old('separator') === null ? ';' : old('separator')) }}" maxlength="5" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="position" class="col-sm-2 control-label"># Regels overslaan</label>
                            <div class="col-sm-10">
                                <input class="form-control" placeholder="Separator" type="number" name="skip" value="{{ (old('skip') === null ? '0' : old('separator')) }}" maxlength="5">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Downloaden</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection