<nav class="navbar navbar-expand-md navbar-light bg-transparent" id="navbar-first">
    <div class="container">
        <a class="navbar-brand" href="{{ routeIf('home') }}">
            @if (Route::is('home'))
                <img src="{{ asset('storage/static/images/nav-logo.png') }}" alt="Logo">
            @else
                <img src="{{ asset('storage/static/images/nav-logov3.png') }}" alt="Logo">
            @endif
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-links">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="my-2 my-lg-0 search-form">
            <quick-search query="{{ request('query') }}" search-url="{{ route('catalog.search') }}"></quick-search>
        </div>
    </div>
</nav>
