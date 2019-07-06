@guest
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mx-auto">{{ __('Klant login') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 18px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @include('components.auth.login.form')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#loginModal').on('shown.bs.modal', function () {
                $('input[name="company"]').trigger('focus')
            })
        </script>
    @endpush
@endguest

<nav class="navbar navbar-expand-md navbar-dark {{ Route::is('home') ? 'bg-transparent' : 'bg-gradient' }}" id="navbar-second">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-links">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Route::is('home') ? 'active' :'' }}">
                    <a class="nav-link" href="{{ route('home') }}">{{ trans('navigation.items.home') }}</a>
                </li>
                <li class="nav-item {{ Route::is('downloads') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('downloads') }}">{{ trans('navigation.items.downloads') }}</a>
                </li>
                <li class="nav-item {{ Route::is('catalog.assortment') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('catalog.assortment') }}">{{ trans('navigation.items.assortment') }}</a>
                </li>
            </ul>

            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    @auth
                        <li class="nav-item mx-md-3 {{ request()->is('cart') ? 'active' : '' }}">
                            <mini-cart :count="{{ $cart->getCount() }}" cart-url="{{ route('checkout.cart') }}"></mini-cart>
                        </li>

                        <li class="d-none d-md-inline nav-item dropdown {{ request()->is('account/*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="far fa-fw fa-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('account.profile') }}">
                                    <i class="far fa-fw fa-sliders-h"></i> {{ __('Mijn Account') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('account.favorites') }}">
                                    <i class="far fa-fw fa-heart"></i> {{ __('Favorieten') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('account.order-history') }}">
                                    <i class="far fa-fw fa-history"></i> {{ __('Bestelhistorie') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('account.addresses') }}">
                                    <i class="far fa-fw fa-address-book"></i> {{ __('Adresboek') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('account.discount') }}">
                                    <i class="far fa-fw fa-percent"></i> {{ __('Kortingsbestand') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit()">
                                    <i class="far fa-fw fa-sign-out"></i> {{ __('Uitloggen') }}
                                </a>
                            </div>
                        </li>

                        <li class="d-inline d-md-none nav-item {{ request()->is('account/*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('account.profile') }}">
                                {{ __('Mijn account') }}
                            </a>
                        </li>

                        <form class="hidden" action="{{ route('auth.logout') }}" method="post" id="logout-form">
                            {{ csrf_field() }}
                        </form>
                    @else
                        <li class="nav-item">
                            <a class="nav-link register-button" href="{{ route('auth.register') }}">{{ __('Registreren') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="event.preventDefault()" data-toggle="modal"
                               data-target="#loginModal" href="{{ route('auth.login', ['toUrl' => url()->current()]) }}">
                                {{ __('Login') }}
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>
