<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Sinds 1956 uw inkoop gemak van bodem tot dak. Uw partner voor leidingsystemen, centrale verwarming, sanitair, non-ferro, dakbedekking en appendages.">
<meta name="keywords" content="Sanitair,Dakbedekking,Non-ferro materiaal,Riolering/HWA systemen,Fittingen,Afsluiters,Gereedschap,Bevestigingsmateriaal,lijm,Rookgasafvoermateriaal">
<meta name="author" content="Thomas Wiringa">
<meta name="theme-color" content="#c2272d">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>WTG Admin - @yield('title')</title>

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.0.10/css/all.css"
      integrity="sha384-KwxQKNj2D0XKEW5O/Y6haRH39PE/xry8SAoLbpbCMraqlX7kUP6KHOnrlrtvuJLR" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,800" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick-theme.css"/>

<link rel="stylesheet" href="{{ mix('assets/css/admin/app.css') }}">

<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

<script src="//cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
<script>
    window.Laravel = {
        isLoggedIn: {{ (int) auth('admin')->check() }}
    };
</script>

@stack('styles')