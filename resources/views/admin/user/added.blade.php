@extends('admin.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="card card-2">
                    <h3>De gebruiker is aangemaakt</h3>

                    <hr />

                    <ul class="list-group">
                        <li class="list-group-item">Login: {{ $input['company_id'] }}</li>
                        <li class="list-group-item">Naam: {{ $input['company_name'] }}</li>
                        <li class="list-group-item">Account: {{ $input['company_id'] }}</li>
                        <li class="list-group-item">Email: {{ $input['email'] }}</li>
                        <li class="list-group-item"><b>Wachtwoord: {{ $password }}</b></li>
                    </ul>

                    <hr />

                    <a class="btn btn-link" href="{{ route('admin.user::manager') }}">Terug naar de beheer pagina</a>
                </div>
            </div>
        </div>
    </div>
@endsection