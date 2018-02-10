@extends('admin.master')

@section('document_start')
    <form action="{{ route('admin.packs::add') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
        <div class="modal fade" id="addProductPack" tabindex="-1" role="dialog" aria-labelledby="#addProductPackLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                        <h4 class="modal-title" id="addProductPackLabel">Actiepakket toevoegen</h4>
                    </div>

                    <div class="modal-body">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Artikelnummer*</label>
                            <div class="col-sm-8">
                                <input onkeyup="getProductName(this)" class="form-control" placeholder="Artikelnummer" type="text" name="product" maxlength="100" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Naam</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="name" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                        <button type="submit" class="btn btn-primary">Toevoegen</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('content')
    <div class="container-fluid" id="packs">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="card card-2">
                    <a data-toggle="modal" data-target="#addProductPack" class="btn btn-block btn-success">
                        <i class="fa fa-plus"></i> Actiepaket toevoegen
                    </a>
                </div>
            </div>
        </div>

        @if (count($packs))
            <div class="row">
                @foreach($packs as $pack)
                    <div class="col-md-4">
                        <div class="card card-2">
                            <div class="actiepaket">
                                <div class="title">
                                    {{ strlen($pack->product->name) > 40 ? substr($pack->product->name, 0 , 37) . "..." : $pack->product->name }}
                                </div>
                                <div class="product-list">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Aantal</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($pack->products as $product)
                                            <tr>
                                                <td>{{ $product->product }}</td>
                                                <td>{{ $product->amount }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="actiepaket-actions">
                                    <a href="{{ route('admin.packs::edit', ['id' =>  $pack->id]) }}" class="btn btn-primary btn-block">Aanpassen</a>
                                    <button onclick="showConfirmationModal(this)" data-id="{{ $pack->id }}" data-name="{{ $pack->name }}" class="btn btn-danger btn-block">Verwijderen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection