<div class="modal fade" id="deleteAccountDialog" tabindex="-2" role="dialog" aria-labelledby="deleteAccount" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" role="form">

                {{ csrf_field() }}
                {{ method_field('delete') }}

                <input type="hidden" value="" id="deleteUsernameInput" name="username">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Sub account verwijderen</h4>
                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">
                        <h4>
                            Waarschuwing! U staat op het punt om het sub-account met gebruikersnaam '<span id="deleteUsername"></span>' te verwijderen.<br />
                            Dit zal niet ongedaan gemaakt kunnen worden, de favorieten en bestelgeschiedenis zullen verloren gaan!<br />
                            Weet u zeker dat u deze gebruiker wilt verwijderen?
                        </h4>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="delete" id="inputManager" value="1" required> Verwijder gebruiker en bijbehorende gegevens
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Annuleren</button>
                        </div>

                        <br class="visible-xs" />

                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-danger btn-block">Verwijderen</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>