<!DOCTYPE html>
<html lang="nl">
    <head>
        <link rel="stylesheet" href="{{ public_path('assets/css/app.css') }}">
    </head>

    <body style="width:25cm; height:29.7cm">
        <header class="mb-5">
            <div class="row">
                <div class="col-4">
                    <img src="{{ Image::make(public_path('img/logo.png'))->encode('data-url') }}" alt="Logo" class="img-fluid" />
                </div>
                <div class="col-8 text-center">
                    @yield('title')
                </div>
            </div>
        </header>

        <section>
            @yield('pre-content')
        </section>

        <main>
            @yield('content')
        </main>
    </body>
</html>
