<div id="content-wrapper">
    @yield('before-content')

    <div class="hidden-sm hidden-xs">
        @include('components.admin.pre-content')
    </div>

    <div id="content" class="animated fadeIn">
        @yield('content')
    </div>

    @yield('after-content')
</div>