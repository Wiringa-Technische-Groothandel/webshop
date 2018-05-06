<nav class="navbar navbar-dark sticky-top" id="navigation">
    <ul class="navbar-nav ml-auto flex-row">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="{{ url('/') }}">
                <i class="fal fa-fw fa-home"></i> Home
            </a>
        </li>
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="#" onclick="document.getElementById('logout-form').submit()">
                <i class="fal fa-fw fa-power-off"></i> Logout
            </a>
        </li>
    </ul>

    <form class="d-none" id="logout-form" method="post" action="{{ route('admin.auth.logout') }}">
        {{ csrf_field() }}
    </form>
</nav>

{{--<div id="header-wrapper">--}}
    {{--@yield('before-header')--}}

    {{--<header id="header">--}}
        {{--<div class="container-fluid">--}}
            {{--<div class="pull-left">--}}
                {{--<ul class="nav nav-pills">--}}
                    {{--<li role="presentation">--}}
                        {{--<a href="#" title="Toggle navigation" id="toggle-navigation"><i class="fa fa-align-justify"></i></a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</div>--}}

            {{--<div class="pull-right">--}}
                {{--<ul class="nav nav-pills">--}}
                    {{--<li role="presentation">--}}
                        {{--<a href="{{ url('/') }}" title="Home"><i class="fal fa-fw fa-home"></i></a>--}}
                    {{--</li>--}}
                    {{--<li role="presentation">--}}
                        {{--<a href="{{ route('admin.dashboard') }}" title="Dashboard"><i class="fal fa-fw fa-dashboard"></i></a>--}}
                    {{--</li>--}}
                    {{--<li role="presentation">--}}
                        {{--<a href="#" data-target="#resetCacheModal" data-toggle="modal" title="Reset cache"><i class="fal fa-fw fa-microchip"></i></a>--}}
                    {{--</li>--}}
                    {{--<li role="presentation">--}}
                        {{--<a href="#" onclick="document.getElementById('logout-form').submit()" title="Logout"><i class="fal fa-fw fa-power-off"></i></a>--}}
                    {{--</li>--}}
                {{--</ul>--}}

                {{--<form style="display: none;" id="logout-form" method="post" action="{{ route('admin.auth.logout') }}">--}}
                    {{--{{ csrf_field() }}--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</header>--}}

    {{--@yield('after-header')--}}
{{--</div>--}}