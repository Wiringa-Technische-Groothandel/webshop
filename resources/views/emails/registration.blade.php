@component('mail::message')

# Webshop registratie

---

## Contactgegevens

@component('mail::table')
| Veld                    | Waarde                                        |
|:----------------------- |:--------------------------------------------- |
| Bedrijfsnaam            | {{ $registration->getContactCompany() }}      |
| Contactpersoon          | {{ $registration->getContactName() }}         |
| Adres                   | {{ $registration->getContactAddress() }}      |
| Plaats                  | {{ $registration->getContactCity() }}         |
| Postcode                | {{ $registration->getContactPostcode() }}     |
| Telefoon bedrijf        | {{ $registration->getContactPhoneCompany() }} |
| Telefoon contactpersoon | {{ $registration->getContactPhone() }}        |
| E-mail                  | {{ $registration->getContactEmail() }}        |
| Website                 | {{ $registration->getContactWebsite() }}      |
@endcomponent

## Vestigingsadres

@component('mail::table')
| Veld                    | Waarde                                     |
|:----------------------- |:------------------------------------------ |
| Adres                   | {{ $registration->getBusinessAddress() }}  |
| Plaats                  | {{ $registration->getBusinessCity() }}     |
| Postcode                | {{ $registration->getBusinessPostcode() }} |
| Telefoon bedrijf        | {{ $registration->getBusinessPhone() }}    |
@endcomponent

## Betalingsgegevens

@component('mail::table')
| Veld | Waarde                                |
|:---- |:------------------------------------- |
| IBAN | {{ $registration->getPaymentIban() }} |
| KVK  | {{ $registration->getPaymentKvk() }}  |
| BTW  | {{ $registration->getPaymentVat() }}  |
@endcomponent

## Overige gegevens

@component('mail::table')
| Veld                                     | Waarde                                                          |
|:---------------------------------------- |:--------------------------------------------------------------- |
| Afwijkend e-mail adres voor facturen     | {{ $registration->getOtherAltEmail() }}                         |
| Digitale orderbevestiging ontvangen      | {{ $registration->getOtherOrderConfirmation() ? 'Ja' : 'Nee' }} |
| Mail ontvangen bij nieuw artikelbestand  | {{ $registration->getOtherMailProductfile() ? 'Ja' : 'Nee' }}   |
@endcomponent

@endcomponent