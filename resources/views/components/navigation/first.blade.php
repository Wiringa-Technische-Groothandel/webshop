<nav class="navbar navbar-expand-md navbar-light bg-transparent" id="navbar-first">
    <div class="container">
        <a class="navbar-brand" href="{{ routeIf('home') }}">
            @if (Route::is('home'))
                <img src="{{ asset('img/nav-logo.png') }}" alt="Logo">
            @else
                <img src="{{ asset('img/nav-logov3.png') }}" alt="Logo">
            @endif
        </a>

        @can('visit admin panel')
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-success" href="{{ routeIf('admin') }}">{{ trans('navigation.items.admin') }}</a>
                </li>
            </ul>
        @endcan

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-links">
            <span class="navbar-toggler-icon"></span>
        </button>

        <form class="form-inline my-2 my-lg-0" action="{{ url('search') }}" role="search">
            <div class="input-group">
                <input type="text" class="form-control" oninput="search.suggest(this)" name="query" placeholder="{{ __('Zoeken') }}"
                       {{--value="{{ request('q') }}" data-suggest-url="{{ route('search::suggest') }}"--}}
                       onblur="search.hideSuggest()" onfocus="search.suggest(this)" />

                <span class="input-group-btn">
                    <button class="btn btn-outline-success" type="button">
                        <i class="fal fa-fw fa-search"></i>
                    </button>
                </span>
            </div>
            <div id="suggest-box" style="display: none;"></div>
        </form>
    </div>
</nav>
