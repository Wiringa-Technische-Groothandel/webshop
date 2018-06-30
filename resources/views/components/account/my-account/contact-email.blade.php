<div class="card">
    <div class="card-header">
        {{ __('Contact e-mail') }}
    </div>
    <div class="card-body">
        <p class="card-text">
            {{ __('Naar dit e-mail adres worden contact gerelateerde mails gestuurd, bijvoorbeeld een wachtwoord reset link.') }}
        </p>

        <div class="alert alert-warning">
            <i class="fal fa-fw fa-exclamation-triangle"></i>

            <b>Let op:</b> {{ __('Zonder contact e-mail kunt u uw wachtwoord niet resetten als u deze bent vergeten.') }}
        </div>

        <input class="form-control" title="Contact email" name="contact_email" oninput="toggleSaveButton()"
               value="{{ $customer->getContact()->getContactEmail() }}" type="email"
               data-initial="{{ $customer->getContact()->getContactEmail() }}"
               data-required="false" />

        <button type="submit" class="btn btn-success my-2" style="display: none;">
            {{ __('Opslaan') }}
        </button>
    </div>
</div>