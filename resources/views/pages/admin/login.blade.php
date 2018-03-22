<!DOCTYPE html>
<html lang="nl">
<head>
    @include('components.head')
</head>
<body>

<div id="app" class="container-fluid">
    <div class="row align-items-center" style="height: 100vh;">
        <div class="col-12 col-md-4 mx-auto">
            <img class="img-fluid mb-3" src="{{ asset('img/logov2.png') }}" alt="WTG Logo">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center font-weight-light">{{ __('Admin login') }}</h3>

                    <hr />

                    @if ($errors->count() > 0)
                        <div class="alert alert-danger">
                            <i class="fa fa-fw fa-exclamation-triangle"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="username">{{ __('Gebruikersnaam') }}</label>
                            <input type="text" value="{{ old('username') }}" class="form-control" name="username" placeholder="{{ __('Gebruikersnaam') }}">
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Wachtwoord') }}</label>
                            <input type="password" class="form-control" name="password" placeholder="{{ __('Wachtwoord') }}">
                        </div>

                        <button type="submit" class="btn btn-outline-success">{{ __('Login') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ elixir('assets/js/admin/app.js') }}"></script>
</body>
</html>