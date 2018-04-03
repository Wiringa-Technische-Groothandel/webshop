<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sinds 1956 uw inkoop gemak van bodem tot dak. Uw partner voor leidingsystemen, centrale verwarming, sanitair, non-ferro, dakbedekking en appendages.">
    <meta name="keywords" content="Sanitair,Dakbedekking,Non-ferro materiaal,Riolering/HWA systemen,Fittingen,Afsluiters,Gereedschap,Bevestigingsmateriaal,lijm,Rookgasafvoermateriaal">
    <meta name="author" content="Thomas Wiringa">

    <title>WTG {{ isset($pagetitle) ? " | " . $pagetitle : '' }}</title>

    @yield('extraCSS')

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        // Google Analytics
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-41373177-1', 'auto');
        ga('set', 'anonymizeIp', true);
	    ga('send', 'pageview');
    </script>
</head>
<body>
    <div class="background"></div>

    @if(!Auth::check())
        @include('components.login')
    @endif

    <nav class="navbar navbar-wtg navbar-static-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand visible-xs" href="{{ url('/') }}">
                    <img src="{{ asset('img/nav-logo.png') }}" alt="Logo">
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav" id="nav-buttons">
                    <li class="@if( Route::current()->getUri() === '/' ) active @endif"><a href="{{ url('/') }}">Home</a></li>
                    <li class="dropdown @if( Route::current()->getUri() === 'about' || Route::current()->getUri() === 'contact' || Route::current()->getUri() === 'assortment' ) active @endif">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Info <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('about') }}">Het bedrijf</a></li>
                            <li><a href="{{ url('contact') }}">Contact</a></li>
                            <li><a href="{{ url('assortment') }}">Assortiment</a></li>
                        </ul>
                    </li>
                    <li class="@if( Route::current()->getUri() === 'downloads' ) active @endif"><a href="/downloads">Downloads</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Webshop <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('webshop') }}">Zoeken</a></li>
                            <li><a href="{{ url('specials') }}">Acties</a></li>
                            <li><a href="{{ url('clearance') }}">Opruiming</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('vacancies') }}">Vacatures</a></li>
                    @if(Auth::check() && Auth::user()->isAdmin)
                        <li class="@if( substr(Route::current()->getUri(), 0, 5) === 'admin' ) active @endif"><a href="{{ url('admin') }}">Admin</a></li>
                    @endif
                </ul>

                <div class="navbar-right " id="nav-utils">
                    <ul class="nav navbar-nav">
                        @if(Auth::check())
                            <li class="@if( Route::current()->getUri() === 'cart' ) active @endif"><a href="{{ url('cart') }}" style="height: 50px">Winkelwagen @if(Cart::count(false) > 0) <span class="badge">{{ Cart::count(false) }}</span> @endif</a></li>
                            <li class="dropdown @if( substr(Route::current()->getUri(), 0, 7) === 'account' ) active @endif">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('account') }}"><span class="glyphicon glyphicon-user"></span> Gegevens</a></li>
                                    <li><a href="{{ url('account/favorites') }}"><span class="glyphicon glyphicon-heart"></span> Favorieten</a></li>
                                    <li><a href="{{ url('account/orderhistory') }}"><span class="glyphicon glyphicon-time"></span> Geschiedenis</a></li>
                                    <li><a href="{{ url('account/discountfile') }}"><span class="glyphicon glyphicon-euro"></span> Kortingsbestand</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{ url('logout') }}"><span class="glyphicon glyphicon-off"></span> Loguit</a></li>
                                </ul>
                            </li>
                        @else
                            <li><a href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
                        @endif
                    </ul>

                    <br />

                    <form action="{{ url('search') }}" method="GET" class="navbar-form hidden-xs" role="search">
              )         {{ csrf_field() }}
                        <div class="form-group search-field has-feedback">
                            <input id="searchInput" value="{{ request('q') }}" name="q" type="text" required="" class="form-control" placeholder="Zoeken">
                            <button type="submit" class="btn btn-link"><i class="glyphicon glyphicon-search form-control-feedback"></i></button>
                        </div>
                    </form>
                </div>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-->
    </nav>

    @include('components.notifications')

    <header class="page-header hidden-xs">
        <div class="container">
            <div class="col-md-5 header-logo">
                <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}" alt="Logo"></a>
            </div>
            <div class="col-md-7">
                <h3>Sinds 1956 uw inkoop gemak van bodem tot dak. Uw partner voor leidingsystemen, centrale verwarming, sanitair, non-ferro, dakbedekking en appendages.</h3>
            </div>
        </div>
    </header>

    <div class="container content">

        @if ((isset($hideTitle) && !$hideTitle) || !isset($hideTitle))
            <div class="row">
                <div class="col-md-12 bg-primary site-title">
                    @yield('title')
                </div>
            </div>
        @endif

        @if( Route::current()->getUri() === '/' )
            <div class="well well-sm text-center ie-only">
                <h4>Voor de beste ervaring met onze website raden wij een moderne browser zoals <a href="https://www.google.com/chrome/browser/desktop/index.html" target="_blank">Google Chrome</a> of <a href="https://www.mozilla.org/nl/firefox/new/" target="_blank">Firefox</a> aan.</h4>
            </div>
        @endif

        @yield('content')

        <hr />

        <footer>
            <div class="text-center">
                <p>Wiringa Technische Groothandel (1956 - {{ date("Y") }}) | <a href="https://lunamoonfang.nl/about" target="_blank">Thomas Wiringa</a> - <a href="https://wiringa.nl/">wiringa.nl</a> | <a href="{{ url('licenses') }}">licenties</a></p>

                <p>
                    <small>
			            Deze site maakt gebruik van <abbr title="Deze cookies houden de login status bij en zorgen voor de essentiele functionaliteit van de website">functionele cookies</abbr> en <abbr title="Deze cookies houden anoniem surfgedrag bij van uitsluitend deze website zodat wij de site beter kunnen laten werken">analytics cookies</abbr>.
			            <br />
                        Al onze leveringen geschieden volgens onze algemene leveringsvoorwaarden, gedeponeerd bij de Kamer van Koophandel te Groningen onder nummer 02023871.
                        <br />
                        Een kopie van deze leveringsvoorwaarden zenden wij u op verzoek toe.
                    </small>
                </p>

                <p><small>Load time: {{ round(microtime(true) - LARAVEL_START, 3) }}s  -  Memory usage: {{ round(memory_get_peak_usage(true) / 1000000, 2) }}MB</small></p>
            </div>

            <script src="{{ elixir('js/application.js') }}"></script>

            @yield('extraJS')
        </footer>
    </div>

    @if(app()->environment() !== 'production')
        <div style="position: fixed;bottom: 20px;right: 20px;background-color: red;color:#333;padding:5px;border:3px solid #333;">{{ ucfirst(app()->environment()) }}</div>
    @endif
</body>
</html>
