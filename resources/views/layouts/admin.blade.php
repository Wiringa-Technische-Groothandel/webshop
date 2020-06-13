<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
          content="Sinds 1956 uw inkoop gemak van bodem tot dak. Uw partner voor leidingsystemen, centrale verwarming, sanitair, non-ferro, dakbedekking en appendages.">
    <meta name="keywords"
          content="Sanitair,Dakbedekking,Non-ferro materiaal,Riolering/HWA systemen,Fittingen,Afsluiters,Gereedschap,Bevestigingsmateriaal,lijm,Rookgasafvoermateriaal">
    <meta name="author" content="Thomas Wiringa">
    <meta name="theme-color" content="#c2272d">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Wiringa Technische Groothandel Admin - @yield('title', $title ?? 'Untitled')</title>

    @if (config('services.sentry.js-dsn'))
        <script src="https://browser.sentry-cdn.com/5.12.1/bundle.min.js" crossorigin="anonymous"
                integrity="sha384-y+an4eARFKvjzOivf/Z7JtMJhaN6b+lLQ5oFbBbUwZNNVir39cYtkjW1r6Xjbxg3">
        </script>
        <script type="application/javascript">
            Sentry.init({dsn: '{{ config('services.sentry.js-dsn') }}'});
        </script>
    @endif

    <link rel="preconnect dns-prefetch" href="https://fonts.googleapis.com">

    <link rel="preload" as="script" href="https://kit.fontawesome.com/4af601a43c.js" crossorigin="anonymous">
    <link rel="preload" as="script" href="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @routes

    <script src="{{ mix('assets/admin/main.js') }}" async defer></script>
</head>

<body style="opacity: 0;">

<div id="app">
    <template v-if="isLoggedIn">
        <top-navigation></top-navigation>

        <div class="container-fluid">
            <div class="row">
                <side-navigation></side-navigation>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3">
                    <router-view></router-view>

                    <notification :php-errors="{{ json_encode($errors->all()) }}"
                                  php-success="{{ session('status') }}"></notification>
                </main>
            </div>
        </div>
    </template>

    <login-form v-else></login-form>
</div>

<script src="https://kit.fontawesome.com/4af601a43c.js" crossorigin="anonymous" defer></script>
<script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>

</body>
</html>
