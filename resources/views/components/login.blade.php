<div class="modal fade" id="loginModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('auth::login') }}" method="POST" class="form form-horizontal">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Login</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">Debiteurnummer</label>
                        <div class="col-sm-8">
                            <input type="text" name="company" class="form-control" placeholder="Debiteurnummer"
                                   autocomplete="off" required value="{{ old('company') }}">
                            <p class="help-block">Bijv. 12345</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="account" class="col-sm-4 control-label">Gebruikersnaam</label>
                        <div class="col-sm-8">
                            <input type="text" name="username" class="form-control" placeholder="Gebruikersnaam"
                                   autocomplete="off" required value="{{ old('username') }}">
                            <p class="help-block">Bijv. "Piet"</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label">Wachtwoord</label>
                        <div class="col-sm-8">
                            <input type="password" name="password" class="form-control" placeholder="Wachtwoord"
                                   aria-describedby="forgotPassword" required>
                            <span id="forgotPassword" class="help-block"><a href="{{ route('auth::password.email') }}">
                                    Wachtwoord vergeten?</a>
                            </span>
                        </div>
                    </div>

                    <div class="checkbox">
                        <div class="col-sm-offset-4 col-sm-8">
                            <label>
                                <input name="remember_me" type="checkbox"> Ingelogd blijven?
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->