@extends('layouts.main')

@section('title', __('Registreren'))

@section('content')
    <h2 class="block-title text-center">
        {{ __('Registratie aanvragen') }}
    </h2>

    <hr />

    <div class="container">
        @include('components.auth.registration.form')
    </div>
@endsection

@push('scripts')
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        (function () {
            'use strict';

            window.addEventListener('load', function() {
                var form = document.getElementById('needs-validation');
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            }, false);

            /**
             * Copy the field values.
             *
             * @return void
             */
            function copyFields () {
                if ($copyContact.is(":checked")) {
                    $businessAddress.val( $contactAddress.val() );
                    $businessCity.val( $contactCity.val() );
                    $businessPostcode.val( $contactPostcode.val() );
                    $businessPhone.val( $contactPhone.val() );
                }
            }

            /**
             * Toggle the readonly state.
             *
             * @return void
             */
            function toggleReadOnly () {
                if ($copyContact.is(":checked")) {
                    copyFields();

                    $businessAddress.attr('readonly', 'readonly');
                    $businessCity.attr('readonly', 'readonly');
                    $businessPostcode.attr('readonly', 'readonly');
                    $businessPhone.attr('readonly', 'readonly');
                } else {
                    $businessAddress.removeAttr('readonly');
                    $businessCity.removeAttr('readonly');
                    $businessPostcode.removeAttr('readonly');
                    $businessPhone.removeAttr('readonly');
                }
            }

            let $copyContact     = $("#copy-contact");
            let $businessAddress      = $('[name="business-address"]');
            let $businessCity         = $('[name="business-city"]');
            let $businessPostcode     = $('[name="business-postcode"]');
            let $businessPhone   = $('[name="business-phone"]');
            let $contactAddress  = $('[name="contact-address"]');
            let $contactCity     = $('[name="contact-city"]');
            let $contactPostcode = $('[name="contact-postcode"]');
            let $contactPhone    = $('[name="contact-phone-company"]');

            $copyContact.on('change', toggleReadOnly);
            $contactAddress.on('input', copyFields);
            $contactCity.on('input', copyFields);
            $contactPostcode.on('input', copyFields);
            $contactPhone.on('input', copyFields);

            toggleReadOnly();
        })();
    </script>
@endpush