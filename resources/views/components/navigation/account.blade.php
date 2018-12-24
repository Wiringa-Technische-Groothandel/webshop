<div class="list-group pb-3">
    <a href="{{ route('account.profile') }}"
       class="list-group-item {{ Route::is('account.profile') ? 'active' : '' }}">
        {{ __('Mijn Account') }}
    </a>

    @can('subaccounts-view')
        <a href="{{ route('account.sub_accounts') }}"
           class="list-group-item {{ Route::is('account.sub_accounts') ? 'active' : '' }}">
            {{ __('Sub-accounts') }}
        </a>
    @endif

    <a href="{{ route('account.change_password') }}"
       class="list-group-item {{ Route::is('account.change_password') ? 'active' : '' }}">
        {{ __('Wachtwoord wijzigen') }}
    </a>

    <a href="{{ route('account.favorites') }}"
       class="list-group-item {{ Route::is('account.favorites') ? 'active' : '' }}">
        {{ __('Favorieten') }}
    </a>

    <a href="{{ route('account.order-history') }}"
       class="list-group-item {{ Route::is('account.order-history') ? 'active' : '' }}">
        {{ __('Bestelhistorie') }}
    </a>

    <a href="{{ route('account.invoices') }}"
       class="list-group-item {{ Route::is('account.invoices') ? 'active' : '' }}">
        {{ __('Facturen') }}
    </a>

    <a href="{{ route('account.addresses') }}"
       class="list-group-item {{ Route::is('account.addresses') ? 'active' : '' }}">
        {{ __('Adressen') }}
    </a>

    <a href="{{ route('account.discount') }}"
       class="list-group-item {{ Route::is('account.discount') ? 'active' : '' }}">
        {{ __('Kortingsbestand genereren') }}
    </a>
</div>
