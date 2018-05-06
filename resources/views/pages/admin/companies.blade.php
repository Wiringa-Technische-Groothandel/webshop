@extends('layouts.admin')

@section('title', __('Klantbeheer'))

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h3>
                            <i class="fal fa-fw fa-building"></i> {{ __('Bedrijven') }}
                        </h3>

                        <hr />

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Naam') }}</th>
                                <th scope="col">{{ __('Klantnummer') }}</th>
                                <th scope="col">{{ __('Aantal gebruikers') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <th scope="row">{{ $company->getId() }}</th>
                                    <td><a href="{{ route('admin.company.edit', ['id' => $company->getId()]) }}">{{ $company->getName() }}</a></td>
                                    <td>{{ $company->getCustomerNumber() }}</td>
                                    <td>{{ $company->getCustomers()->count() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <hr />

                        {{ $companies->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection