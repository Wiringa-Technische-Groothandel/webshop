@extends('layouts.admin')

@section('title', __('Inhoud beheren'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card card-2">
                    @if ($blocks->isNotEmpty())
                        <block url="{{ route('admin.content.block') }}" :blocks="{{ $blocks }}"></block>
                    @else
                        <h3><i class="fal fa-fw fa-edit"></i> {{ __('Blokken') }}</h3>

                        <hr />

                        <div class="alert alert-warning">
                            {{ __('Er zijn nog geen beheerbare blokken aangemaakt.') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card card-2">
                    <descriptions url="{{ route('admin.content.description') }}"></descriptions>
                </div>
            </div>
        </div>
    </div>
@endsection