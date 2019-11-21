<!doctype html>
<html lang="nl">
    <head>
        @include('components.head')
    </head>

    <body>
        <div id="app">
            @yield('before_content')

            @include('components.header')

            <main>
                @yield('content')
            </main>

            <footer-block></footer-block>

            @yield('after_content')

            @if(!app()->environment('production'))
                <div style="position: fixed;bottom: 20px;right: 20px;background-color: red;color:#333;padding:5px;border:3px solid #333;">{{ ucfirst(app()->environment()) }}</div>
            @endif

            <notification :php-errors="{{ $errors->toJson() }}"
                          php-success="{{ session('status') }}"></notification>
        </div>

        @stack('scripts')
    </body>
</html>
