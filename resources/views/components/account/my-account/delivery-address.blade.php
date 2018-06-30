<div class="card">
    <div class="card-header">
        {{ __('Standaard afleveradres') }}
    </div>
    <div class="card-body">
        @if ($address)
            <address>
                <b>{{ $address->getName() }}</b><br />
                {{ $address->getStreet() }} <br />
                {{ $address->getPostcode() }} {{ $address->getCity() }} <br />

                @if ($address->getPhone())
                    <i class="fal fa-fw fa-phone"></i> {{ $address->getPhone() }} <br />
                @endif

                @if ($address->getMobile())
                    <i class="fal fa-fw fa-mobile"></i> {{ $address->getMobile() }}
                @endif
            </address>
        @else
            <div class="alert alert-warning">
                {{ __('U hebt nog geen standaard afleveradres geselecteerd.') }}
            </div>
        @endif

        <a href="{{ route('account.addresses') }}">
            {{ __('Standaard adres wijzigen') }}
        </a>
    </div>
</div>