<nav class="navbar navbar-expand-md navbar-dark {{ Route::is('home') ? 'bg-transparent' : 'bg-gradient' }}" id="navbar-second">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-links">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Route::is('home') ? 'active' :'' }}">
                    <a class="nav-link" href="{{ routeIf('home') }}">{{ trans('navigation.items.home') }}</a>
                </li>
                <li class="nav-item {{ Route::is('downloads') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ routeIf('downloads') }}">{{ trans('navigation.items.downloads') }}</a>
                </li>
                <li class="nav-item {{ Route::is('catalog.assortment') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ routeIf('catalog.assortment') }}">{{ trans('navigation.items.assortment') }}</a>
                </li>
            </ul>

            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    @auth
                        <li class="nav-item mx-3 {{ request()->is('cart') ? 'active' : '' }}">
                            <mini-cart :count="{{ $cart->count() }}" cart-url="{{ route('checkout.cart') }}"></mini-cart>
                        </li>
                        <li class="nav-item dropdown {{ request()->is('account/*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="far fa-fw fa-user"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ routeIf('account.dashboard') }}">
                                    <i class="far fa-fw fa-sliders-h"></i> {{ trans('navigation.items.dashboard') }}
                                </a>
                                <a class="dropdown-item" href="{{ routeIf('account.favorites') }}">
                                    <i class="far fa-fw fa-heart"></i> {{ trans('navigation.items.favorites') }}
                                </a>
                                <a class="dropdown-item" href="{{ routeIf('account.order-history') }}">
                                    <i class="far fa-fw fa-history"></i> {{ trans('navigation.items.order_history') }}
                                </a>
                                <a class="dropdown-item" href="{{ routeIf('account.discountfile') }}">
                                    <i class="far fa-fw fa-percent"></i> {{ trans('navigation.items.discount_file') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit()">
                                    <i class="far fa-fw fa-sign-out"></i> {{ trans('navigation.items.logout') }}
                                </a>
                            </div>
                        </li>

                        <form class="hidden" action="{{ routeIf('auth.logout') }}" method="post" id="logout-form">
                            {{ csrf_field() }}
                        </form>
                    @else
                        <li class="nav-item">
                            <a class="nav-link register-button" href="{{ routeIf('auth.register.form') }}">{{ trans('navigation.items.register') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ routeIf('auth.login', ['toUrl' => url()->current()]) }}">{{ trans('navigation.items.login') }}</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>
