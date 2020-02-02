<div class="list-group pb-3">
    <a href="{{ route('account.profile') }}"
       class="list-group-item {{ route_class('account.profile') }}">
        {{ __('Mijn Account') }}
    </a>

    @can('subaccounts-view')
        <a href="{{ route('account.sub_accounts') }}"
           class="list-group-item {{ route_class('account.sub_accounts') }}">
            {{ __('Sub-accounts') }}
        </a>
    @endif

    <a href="{{ route('account.change_password') }}"
       class="list-group-item {{ route_class('account.change_password') }}">
        {{ __('Wachtwoord wijzigen') }}
    </a>

    <a href="{{ route('account.favorites') }}"
       class="list-group-item {{ route_class('account.favorites') }}">
        {{ __('Favorieten') }}
    </a>

    <a href="{{ route('account.order-history') }}"
       class="list-group-item {{ route_class('account.order-history') }}">
        {{ __('Bestelhistorie') }}
    </a>

    <a href="{{ route('account.invoices') }}"
       class="list-group-item {{ route_class('account.invoices') }}">
        {{ __('Facturen') }}
    </a>

    <a href="{{ route('account.addresses') }}"
       class="list-group-item {{ route_class('account.addresses') }}">
        {{ __('Adresboek') }}
    </a>

    <a href="{{ route('account.discount') }}"
       class="list-group-item {{ route_class('account.discount') }}">
        {{ __('Kortingsbestand genereren') }}
    </a>
</div>
