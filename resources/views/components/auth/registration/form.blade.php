<form method="post" id="needs-validation" novalidate>
    {{ csrf_field() }}
    {{ method_field('put') }}

    <p class="help-block">
        {{ __('Velden gemarkeerd met een * zijn verplicht.') }}
    </p>

    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <h3>{{ __('Contactgegevens') }}</h3>

            <section id="contact" class="mt-5">
                <div class="form-group">
                    <input type="text" class="form-control" name="contact-company" value="{{ old('contact-company') }}" required>
                    <label class="control-label" for="contact-company">{{ __('Naam bedrijf') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een bedrijfsnaam in') }}
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="contact-name" value="{{ old('contact-name') }}" required>
                    <label class="control-label" for="contact-name">{{ __('Naam contactpersoon') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub de naam van de contactpersoon in') }}
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="contact-address" value="{{ old('contact-address') }}" required>
                    <label class="control-label" for="contact-address">{{ __('Straat + Huisnummer') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een adres in') }}
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <input type="text" class="form-control" name="contact-city" value="{{ old('contact-city') }}" required>
                            <label class="control-label" for="contact-city">{{ __('Plaats') }} *</label>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een plaats in') }}
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <input type="text" class="form-control" name="contact-postcode" maxlength="6" value="{{ old('contact-postcode') }}" required>
                            <label class="control-label" for="contact-postcode">{{ __('Postcode (vb. 1234AB)') }} *</label>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een postcode in') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <input type="tel" class="form-control" name="contact-phone-company" value="{{ old('contact-phone-company') }}" required>
                            <label class="control-label" for="contact-phone-company">{{ __('Telefoon bedrijf') }} *</label>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een telefoonnummer in') }}
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <input type="tel" class="form-control" name="contact-phone" value="{{ old('contact-phone') }}">
                            <label class="control-label" for="contact-phone">{{ __('Telefoon contactpersoon') }}</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" name="contact-email" value="{{ old('contact-email') }}" required>
                    <label class="control-label" for="contact-email">{{ __('E-Mail') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een e-mail adres in') }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">https://</div>
                        </div>
                        <input type="text" class="form-control" placeholder="{{ __('Website url') }}"
                               name="contact-website" value="{{ old('contact-website') }}">
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-md-6">
            <h3>{{ __('Vestigingsadres') }}</h3>

            <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" name="copy-contact" id="copy-contact" {{ old('copy-contact') ? 'checked' : '' }}>
                <label class="custom-control-label" for="copy-contact">{{ __('Overnemen van contactgegevens') }}</label>
            </div>

            <section id="location">
                <div class="form-group">
                    <input type="text" class="form-control" name="business-address" value="{{ old('business-address') }}" required>
                    <label class="control-label" for="business-address">{{ __('Straat + Huisnummer') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een adres in') }}
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <input type="text" class="form-control" name="business-city" value="{{ old('business-city') }}" required>
                            <label class="control-label" for="business-city">{{ __('Plaats') }} *</label>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een plaats in') }}
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <input type="text" class="form-control" name="business-postcode" maxlength="6" value="{{ old('business-postcode') }}" required>
                            <label class="control-label" for="business-postcode">{{ __('Postcode') }} *</label>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een postcode in') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="tel" class="form-control" name="business-phone" value="{{ old('business-phone') }}" required>
                    <label class="control-label" for="business-phone">{{ __('Telefoon bedrijf') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een telefoonnummer in') }}
                    </div>
                </div>
            </section>
        </div>
    </div>

    <hr />

    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <h3>{{ __('Betalingsgegevens') }}</h3>

            <section id="payment" class="mt-5">
                <div class="form-group">
                    <input type="text" class="form-control" name="payment-iban" value="{{ old('payment-iban') }}" required>
                    <label class="control-label" for="iban">{{ __('IBAN nummer') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een IBAN nummer in') }}
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="payment-kvk" value="{{ old('payment-kvk') }}" required>
                    <label class="control-label" for="kvk">{{ __('KVK nummer') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een KVK nummer in') }}
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="payment-vat" value="{{ old('payment-vat') }}" required>
                    <label class="control-label" for="vat">{{ __('BTW nummer') }} *</label>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een BTW nummer in') }}
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-md-6">
            <h3>{{ __('Overige gegevens') }}</h3>

            <section id="other" class="mt-5">
                <div class="form-group mb-3">
                    <input type="email" class="form-control" name="other-alt-email" value="{{ old('other-alt-email') }}">
                    <label class="control-label" for="other-alt-email">{{ __('Afwijkend e-mail adres voor facturen') }}</label>
                </div>

                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" name="other-order-confirmation" id="other-order-confirmation" {{ old('order-order-confirmation') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="other-order-confirmation">{{ __('Digitale orderbevestiging ontvangen') }}</label>
                </div>

                <div class="g-recaptcha mb-3" data-sitekey="{{ config('wtg.recaptcha.site-key') }}"></div>
            </section>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-md-6 offset-md-3">
            <button class="btn btn-outline-success btn-block" type="submit">
                <i class="fal fa-fw fa-paper-plane"></i> {{ __('Versturen') }}
            </button>
        </div>
    </div>
</form>