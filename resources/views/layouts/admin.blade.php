<!DOCTYPE html>
<html lang="nl">
<head>
    @include('components.admin.head')
</head>
<body>

@yield('pre-content')

@include('components.admin.navigation')

<div class="container-fluid">
    <div class="row">
        @include('components.admin.sidebar')

        <main id="app" role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3">
            @yield('content')

            <notification :php-errors="{{ $errors->toJson() }}"
                          php-success="{{ session('status') }}"></notification>
        </main>
    </div>
</div>

<script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>

@stack('scripts')

</body>
</html>