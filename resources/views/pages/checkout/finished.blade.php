@extends('layouts.main')

@section('title', __('Bestelling / Afgerond'))

@section('content')
    <h2 class="text-center block-title" id="catalog">{{ __('Bestelling afgerond') }}</h2>

    <div class="container">
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 33%; height: 20px">
                {{ __('Overzicht') }}
            </div>
            <div class="progress-bar bg-success" role="progressbar" style="width: 34%; height: 20px">
                {{ __('Adres') }}
            </div>
            <div class="progress-bar bg-primary" role="progressbar" style="width: 33%; height: 20px">
                {{ __('Afgerond') }}
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col-sm-8">
                <h3 class="mb-3"><i class="fal fa-fw fa-box-check"></i> {{ __('Bedankt voor uw bestelling!') }}</h3>

                <p>{{ __('Uw bestelling is in goede orde ontvangen en wij zullen deze zo spoedig mogelijk verwerken!') }}</p>
            </div>

            <div class="col-sm-4">
                <h3 class="mb-3"><i class="fal fa-fw fa-map-marker-alt"></i> {{ __('Afleveradres') }}</h3>

                <address>
                    <b>{{ $order->getName() }}</b><br />
                    {{ $order->getStreet() }} <br />
                    {{ $order->getPostcode() }} {{ $order->getCity() }}
                </address>
            </div>
        </div>

        <hr />

        <div class="row mb-3">
            <div class="col-md-12">
                <h3 class="mb-3"><i class="fal fa-fw fa-th-list"></i> {{ __('Uw bestelling') }}</h3>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Productnummer') }}</th>
                            <th>{{ __('Naam') }}</th>
                            <th class="text-right">{{ __('Prijs') }}</th>
                            <th class="text-right">{{ __('Aantal') }}</th>
                            <th class="text-right">{{ __('Subtotaal') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->getItems() as $item)
                            <tr>
                                <td>{{ $item->getSku() }}</td>
                                <td>{{ $item->getName() }}</td>
                                <td class="text-right"><i class="fal fa-fw fa-euro-sign"></i>{{ format_price($item->getPrice()) }}</td>
                                <td class="text-right">{{ $item->getQuantity() }}</td>
                                <td class="text-right"><i class="fal fa-fw fa-euro-sign"></i>{{ format_price($item->getSubtotal()) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection