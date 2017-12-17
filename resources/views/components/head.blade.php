<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Sinds 1956 uw inkoop gemak van bodem tot dak. Uw partner voor leidingsystemen, centrale verwarming, sanitair, non-ferro, dakbedekking en appendages.">
<meta name="keywords" content="Sanitair,Dakbedekking,Non-ferro materiaal,Riolering/HWA systemen,Fittingen,Afsluiters,Gereedschap,Bevestigingsmateriaal,lijm,Rookgasafvoermateriaal">
<meta name="author" content="Thomas Wiringa">
<meta name="theme-color" content="#c2272d">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>WTG - @yield('title')</title>

<link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,800" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick-theme.css"/>
<link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

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

<script>
    window.Laravel = {
        isLoggedIn: {{ (int) auth()->check() }}
    };
</script>

@if (auth()->check() && auth()->user()->hasRole(\WTG\Models\Customer::CUSTOMER_ROLE_SUPER_ADMIN))
    <script src="//cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
@endif