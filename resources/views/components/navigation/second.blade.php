@guest
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mx-auto">{{ __('Klant login') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="position: absolute; right: 20px; top: 18px;">
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

<nav class="navbar navbar-expand-md navbar-dark {{ route_class('home', 'bg-transparent', 'bg-gradient') }}"
     id="navbar-second">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-links">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ route_class('home') }}">
                    <a class="nav-link" href="{{ route('home') }}">{{ trans('navigation.items.home') }}</a>
                </li>

                <li class="nav-item {{ route_class('downloads') }}">
                    <a class="nav-link" href="{{ route('downloads') }}">{{ trans('navigation.items.downloads') }}</a>
                </li>

                <li class="nav-item {{ route_class('catalog.assortment') }}">
                    <span class="nav-link" onclick="window.toggleMenu()"
                          data-href="{{ route('catalog.assortment') }}">{{ trans('navigation.items.assortment') }}</span>
                </li>
            </ul>

            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    @auth
                        <li class="nav-item mx-md-3 {{ route_class('cart') }}">
                            <mini-cart :count="{{ $cart->getCount() }}"
                                       cart-url="{{ route('checkout.cart') }}"></mini-cart>
                        </li>

                        <li class="d-none d-md-inline nav-item dropdown {{ route_class('account/*') }}">
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

                                <a class="dropdown-item" href="#"
                                   onclick="document.getElementById('logout-form').submit()">
                                    <i class="far fa-fw fa-sign-out"></i> {{ __('Uitloggen') }}
                                </a>
                            </div>
                        </li>

                        <li class="d-inline d-md-none nav-item {{ route_class('account/*') }}">
                            <a class="nav-link" href="{{ route('account.profile') }}">
                                {{ __('Mijn account') }}
                            </a>
                        </li>

                        <form class="hidden" action="{{ route('auth.logout') }}" method="post" id="logout-form">
                            {{ csrf_field() }}
                        </form>
                    @else
                        <li class="nav-item">
                            <a class="nav-link register-button"
                               href="{{ route('auth.register') }}">{{ __('Registreren') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" onclick="event.preventDefault()" data-toggle="modal"
                               data-target="#loginModal"
                               href="{{ route('auth.login', ['toUrl' => url()->current()]) }}">
                                {{ __('Login') }}
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>

    <div id="assortment-menu" style="
        display:none;
        position: absolute;
        left: 0;
        right: 0;
        top: 58px;
        z-index: 100;
        box-shadow: 0 5px 5px -2px rgba(0,0,0,0.5);
        padding: 20px 0;
        background: white;">

        <div class="container position-relative" id="category-list-wrapper">
            @php
                /** @var \WTG\Catalog\Model\Category $rootCategory */
                $rootCategory = app(\WTG\Catalog\CategoryManager::class)->loadCategoryTree();
            @endphp

            <ul class="list-group list-group-flush d-inline-flex" id="category-list">
                @foreach($rootCategory->getChildren() as $level1Child)
                    @if ($level1Child->getChildren()->isNotEmpty())
                        <li onmouseenter="window.showCategoryBlock(this)"
                            onmouseleave="window.hideCategoryBlock(this)"
                            class="list-group-item position-static list-group-item-action p-2 border-0"
                            data-target="#category-{{ $level1Child->getCode() }}">
                            <span>{{ $level1Child->getName() }}</span>

                            <div class="list-group list-group-flush position-absolute" style="top:0; display: none;" id="category-{{ $level1Child->getCode() }}">
                                <div class="d-flex" style="justify-content: space-around; align-items: center; flex-wrap: wrap">
                                    @foreach($level1Child->getChildren() as $level2Child)
                                        @if ($level2Child->getChildren()->isNotEmpty())
                                            <div class="d-inline-block" style="flex: 0 0 25%">
                                                <b>{{ $level2Child->getName() }}</b>

{{--                                                <ul>--}}
{{--                                                    @foreach($level2Child->getChildren() as $level3Child)--}}
{{--                                                        <li>{{ $level3Child->getName() }}</li>--}}
{{--                                                    @endforeach--}}
{{--                                                </ul>--}}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        window.toggleMenu = function () {
            var $menu = $('#assortment-menu');
            $menu.toggle();
        };

        window.showCategoryBlock = function (el) {
            var $this = $(el);
            var $target = $($this.data('target'));
            $target.css({
                left: $('#category-list').width(),
                width: $('#category-list-wrapper').width() - $('#category-list').width(),
                height: $('#category-list').height()
            });

            $target.show();
        };

        window.hideCategoryBlock = function (el) {
            var $this = $(el);
            var $target = $($this.data('target'));

            $target.hide();
        };
    </script>
@endpush
