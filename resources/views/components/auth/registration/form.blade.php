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
                    <label for="contact-company">{{ __('Naam bedrijf') }} *</label>
                    <input type="text" class="form-control" name="contact-company" placeholder="{{ __('Naam bedrijf') }}" value="{{ old('contact-company') }}" required>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een bedrijfsnaam in') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact-name">{{ __('Naam contactpersoon') }} *</label>
                    <input type="text" class="form-control" name="contact-name" placeholder="{{ __('Naam contactpersoon') }}" value="{{ old('contact-name') }}" required>
                    <div class="invalid-feedback">
                        {{ __('Vul aub de naam van de contactpersoon in') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact-address">{{ __('Straat + Huisnummer') }} *</label>
                    <input type="text" class="form-control" name="contact-address" placeholder="{{ __('Straat + Huisnummer') }}" value="{{ old('contact-address') }}" required>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een adres in') }}
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label for="contact-city">{{ __('Plaats') }} *</label>
                            <input type="text" class="form-control" name="contact-city" placeholder="{{ __('Plaats') }}" value="{{ old('contact-city') }}" required>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een plaats in') }}
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="contact-postcode">{{ __('Postcode') }} *</label>
                            <input type="text" class="form-control" name="contact-postcode" placeholder="YYYYXX" maxlength="6" value="{{ old('contact-postcode') }}" required>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een postcode in') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label for="contact-phone-company">{{ __('Telefoon bedrijf') }} *</label>
                            <input type="tel" class="form-control" name="contact-phone-company" placeholder="{{ __('Telefoon bedrijf') }}" value="{{ old('contact-phone-company') }}" required>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een telefoonnummer in') }}
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="contact-phone">{{ __('Telefoon contactpersoon') }}</label>
                            <input type="tel" class="form-control" name="contact-phone" placeholder="{{ __('Telefoon contactpersoon') }}" value="{{ old('contact-phone') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact-email">{{ __('E-Mail') }} *</label>
                    <input type="email" class="form-control" name="contact-email" placeholder="{{ __('E-Mail') }}" value="{{ old('contact-email') }}" required>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een e-mail adres in') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact-website">{{ __('Website url') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">https://</div>
                        </div>
                        <input type="url" class="form-control" placeholder="{{ __('Website url') }}" name="contact-website" value="{{ old('contact-website') }}">
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
                    <label for="business-address">{{ __('Straat + Huisnummer') }} *</label>
                    <input type="text" class="form-control" name="business-address" placeholder="{{ __('Straat + Huisnummer') }}" value="{{ old('business-address') }}" required>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een adres in') }}
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label for="business-city">{{ __('Plaats') }} *</label>
                            <input type="text" class="form-control" name="business-city" placeholder="{{ __('Plaats') }}" value="{{ old('business-city') }}" required>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een plaats in') }}
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="business-postcode">{{ __('Postcode') }} *</label>
                            <input type="text" class="form-control" name="business-postcode" placeholder="YYYYXX" maxlength="6" value="{{ old('business-postcode') }}" required>
                            <div class="invalid-feedback">
                                {{ __('Vul aub een postcode in') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="business-phone">{{ __('Telefoon bedrijf') }} *</label>
                    <input type="tel" class="form-control" name="business-phone" placeholder="{{ __('Telefoon bedrijf') }}" value="{{ old('business-phone') }}" required>
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
                    <label for="iban">{{ __('IBAN nummer') }} *</label>
                    <input type="text" class="form-control" name="payment-iban" placeholder="{{ __('IBAN nummer') }}" value="{{ old('payment-iban') }}" required>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een IBAN nummer in') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="kvk">{{ __('KVK nummer') }} *</label>
                    <input type="text" class="form-control" name="payment-kvk" placeholder="{{ __('KVK nummer') }}" value="{{ old('payment-kvk') }}" required>
                    <div class="invalid-feedback">
                        {{ __('Vul aub een KVK nummer in') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="vat">{{ __('BTW nummer') }} *</label>
                    <input type="text" class="form-control" name="payment-vat" placeholder="{{ __('BTW nummer') }}" value="{{ old('payment-vat') }}" required>
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
                    <label for="other-alt-email">{{ __('Afwijkend e-mail adres voor facturen') }}</label>
                    <input type="email" class="form-control" name="other-alt-email" placeholder="{{ __('E-Mail') }}" value="{{ old('other-alt-email') }}">
                </div>

                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" name="other-order-confirmation" id="other-order-confirmation" {{ old('order-order-confirmation') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="other-order-confirmation">{{ __('Digitale orderbevestiging ontvangen') }}</label>
                </div>

                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" name="other-mail-productfile" id="other-mail-productfile" {{ old('other-mail-productfile') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="other-mail-productfile">{{ __('Mail ontvangen bij nieuw artikelbestand') }}</label>
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