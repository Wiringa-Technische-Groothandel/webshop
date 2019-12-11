<!DOCTYPE html>
<html lang="nl">
    <head>
        <link rel="stylesheet" href="{{ public_path('assets/css/app.css') }}">
    </head>

    <body  style="opacity: 0;">
        <header class="mb-5">
            <div class="row">
                <div class="col-4">
                    @php
                        $imageSrc = cache()->rememberForever('images.email-logo', function () {
                            return Image::make(storage_path('app/public/static/images/logo.png'))->encode('data-url');
                        });
                    @endphp
                    <img src="{{ $imageSrc }}" alt="Logo" class="img-fluid" />
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
