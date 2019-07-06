@extends('layouts.admin')

@section('title', __('Actiepakket aanpassen'))

@section('pre-content')
    @include('components.admin.packs.pack.addProductModal')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="title text-center">
                            <h5>{{ $pack->getProduct()->getName() }}</h5>
                        </div>

                        <div class="product-list">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Aantal') }}</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($pack->getItems() as $item)
                                    <tr>
                                        <td>{{ $item->getProduct()->getSku() }}</td>
                                        <td>{{ $item->getAmount() }}</td>
                                        <td class="py-0 align-middle text-right" style="width: 50px;">
                                            <form method="post" class="m-0" onsubmit="return confirm('Weet u zeker dat u product {{ $item->getProduct()->getSku() }} wilt verwijderen?');">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <input type="hidden" name="item_id" value="{{ $item->getId() }}" />

                                                <button class="btn btn-raised btn-danger"><i class="far fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col">
                                <a href="{{ route('admin.packs') }}" class="btn btn-primary btn-block">
                                    {{ __('Terug naar overzicht') }}
                                </a>
                            </div>

                            <div class="col">
                                <a href="#" data-toggle="modal" data-target="#addProductModal" class="btn btn-success btn-raised btn-block">
                                    {{ __('Product toevoegen') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection