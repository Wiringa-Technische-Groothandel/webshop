<!DOCTYPE html>
<html lang="nl">
    <head>
        <link rel="stylesheet" href="{{ base_path('node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
    </head>

    <body style="width:25cm; height:29.7cm">
        <header class="mb-5">
            <div class="row clearfix">
                <div class="col-4 float-left">
                    @php
                        $imageSrc = cache()->rememberForever('images.email-logo', function () {
                            return Image::make(storage_path('app/public/static/images/logo.png'))->encode('data-url');
                        });
                    @endphp
                    <img src="{{ $imageSrc }}" alt="Logo" class="img-fluid" />
                </div>

                <div class="col-8 float-right text-right">
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
