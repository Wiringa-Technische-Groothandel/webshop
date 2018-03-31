<div class="card">
    <div class="card-header">
        {{ __('Orderbevestiging e-mail') }}
    </div>

    <div class="card-body">
        <p class="card-text">
            {{ __('Naar dit e-mail adres worden order gerelateerde mails gestuurd, zoals bijvoorbeeld als er een bestelling geplaatst wordt.') }}
        </p>

        <input class="form-control" title="Order email" name="order_email" oninput="toggleSaveButton()"
               value="{{ $customer->getContact()->getOrderEmail() }}" type="email"
               data-initial="{{ $customer->getContact()->getOrderEmail() }}"
               data-required="false" />

        <button type="submit" class="btn btn-success my-2" style="display: none;">
            {{ __('Opslaan') }}
        </button>
    </div>
</div>