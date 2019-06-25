@component('mail::message')

# Bestelbevestiging

---

## Afleveradres

@component('mail::table')
|                   |                                       |
|:----------------- |:------------------------------------- |
| Bedrijf           | {{ $order->getCompany()->getName() }} |
| Debiteurnummer    | {{ $order->getCustomerNumber() }}     |
| Adres             | {{ $order->getStreet() }}             |
| Plaats            | {{ $order->getCity() }}               |
| Postcode          | {{ $order->getPostcode() }}           |
@endcomponent

@if ($order->getComment())

## Opmerking

@component('mail::panel')

{{ $order->getComment() }}

@endcomponent

@endif

## Producten

@component('mail::table')

| SKU                     | Omschrijving           |                     Aantal |                       Subtotaal |
|:----------------------- |:----------------------:| --------------------------:| -------------------------------:|
@foreach($order->getItems() as $item)
| {{ $item->getSku() }}   | {{ $item->getName() }} | {{ $item->getQuantity() }} | &euro; {{ number_format($item->getSubtotal(), 2) }} |
@endforeach
|                         |                        |                            | <b>Totaal</b> |
|                         |                        |                            | &euro; <b>{{ number_format($order->getGrandTotal(), 2) }}</b> |

@endcomponent

@endcomponent