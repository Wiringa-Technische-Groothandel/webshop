<!doctype html>
<html lang="nl">
    <head>
        @include('components.head')
    </head>

    <body id="error-body">
        <main class="text-center">
            <div id="background"></div>

            @yield('content')
        </main>

        @if(!app()->environment('production'))
            <div style="position: fixed;bottom: 20px;right: 20px;background-color: red;color:#333;padding:5px;border:3px solid #333;">{{ ucfirst(app()->environment()) }}</div>
        @endif

        @stack('scripts')
    </body>
</html>
