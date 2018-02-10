    <h3>
    <i class="fa fa-fw fa-user-plus"></i> Debiteur toevoegen/aanpassen
</h3>

<hr />

<form action="{{ route('admin.user::update') }}" method="POST" class="form form-horizontal" id="addUserForm">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="company_id" class="col-sm-4 control-label">Debiteurnummer</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Debiteurnummer" type="number" name="company_id" id="inputCompanyId" value="{{ old('company_id') }}" required="" autocomplete="off">
        </div>
    </div>

    <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Naam bedrijf</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Naam bedrijf" type="text" name="company_name" id="inputCompanyName" value="{{ old('company_name') }}" required="">
        </div>
    </div>

    <hr />

    <div class="form-group">
        <label for="address" class="col-sm-4 control-label">Adres</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Adres" type="text" name="address" id="inputAddress" value="{{ old('address') }}" required="">
        </div>
    </div>

    <div class="form-group">
        <label for="postcode" class="col-sm-4 control-label">Postcode</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Postcode" type="text" name="postcode" id="inputPostcode" value="{{ old('postcode') }}" required="">
        </div>
    </div>

    <div class="form-group">
        <label for="city" class="col-sm-4 control-label">Plaats</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Plaats" type="text" name="city" id="inputCity" value="{{ old('city') }}" required="">
        </div>
    </div>

    <hr />

    <div class="form-group">
        <label for="username" class="col-sm-4 control-label">Username</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Username" type="text" id="inputUsername" value="{{ old('company_id') }}" disabled="">
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-4 control-label">Email</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Email" type="email" name="email" id="inputEmail" value="{{ old('email') }}" required="">
        </div>
    </div>

    <div class="form-group">
        <label for="active" class="col-sm-4 control-label">Actief?</label>
        <div class="col-sm-4">
            <select name="active" class="form-control" id="inputActive">
                <option value="0">Nee</option>
                <option value="1">Ja</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4">
            <button id="deleteCompanyButton"
                    type="button"
                    class="btn btn-block btn-lg btn-danger"
                    disabled="disabled"
                    data-toggle="modal"
                    data-target="#deleteUserModal">
                <i class="fa fa-remove"></i> Verwijderen
            </button>
        </div>
        <div class="col-sm-8">
            <button type="submit" class="btn btn-block btn-lg btn-success" name="update">
                Toevoegen/wijzigen
            </button>
        </div>
    </div>
</form>