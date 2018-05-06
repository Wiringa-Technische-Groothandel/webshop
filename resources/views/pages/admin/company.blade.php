@extends('layouts.admin')

@section('title', __('Klant :customer', ['customer' => $company->getCustomerNumber()]))

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <a class="btn btn-default" href="{{ url()->previous() }}">
                            <i class="fal fa-fw fa-chevron-left"></i> {{ __('Terug naar overzicht') }}
                        </a>

                        <h3 class="d-inline-block float-right">{{ $company->getCustomerNumber() }} - {{ $company->getName() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6">
                @include('components.admin.company.details')
            </div>

            <div class="col-sm-6">
                @include('components.admin.company.sales')
            </div>
        </div>
    </div>
@endsection

