<!DOCTYPE html>
<html lang="nl">
<head>
    @include('components.admin.head')
</head>
<body>
@yield('document_start')

<div id="app" class="container-fluid">
    @include('components.admin.navigation')

    <div id="page-wrapper">
        @yield('before-page')

        <div id="page">
            @include('components.admin.header')

            @include('components.admin.content')
        </div>

        @yield('after-page')
    </div>

    <notification :php-errors="{{ $errors->toJson() }}"
                  php-success="{{ session('status') }}"></notification>
</div>

<script src="{{ elixir('assets/js/admin/app.js') }}"></script>
<script>
    function makeChart (context, type, data, options) {
        options = options ? options : {};

        return new Chart(context, {
            type: type,
            data: data,
            options: options
        });
    }
</script>

@yield('document_end')
</body>
</html>