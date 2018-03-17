<div id="navigation-wrapper">
    <div id="navigation">
        <div class="title">
            <img src="{{ asset('img/logo.png') }}" />
        </div>

        <ul class="nav nav-pills flex-column animated fadeIn">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fal fa-fw fa-dashboard"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.import') ? 'active' : '' }}" href="{{ route('admin.import') }}">
                    <i class="fal fa-fw fa-download"></i> Importeren
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.company-manager') ? 'active' : '' }}" href="{{ route('admin.company-manager') }}">
                    <i class="fal fa-fw fa-users"></i> Klantbeheer
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.carousel') ? 'active' : '' }}" href="{{ route('admin.carousel') }}">
                    <i class="fal fa-fw fa-image"></i> Carousel
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.export') ? 'active' : '' }}" href="{{ route('admin.export') }}">
                    <i class="fal fa-fw fa-cogs"></i> Genereren
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.content') ? 'active' : '' }}" href="{{ route('admin.content') }}">
                    <i class="fal fa-fw fa-pencil"></i> Inhoud aanpassen
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.packs') ? 'active' : '' }}" href="{{ route('admin.packs') }}">
                    <i class="fal fa-fw fa-star"></i> Actiepaketten
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.cache') ? 'active' : '' }}" href="{{ route('admin.cache') }}">
                    <i class="fal fa-fw fa-microchip"></i> Cache
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.email') ? 'active' : '' }}" href="{{ route('admin.email') }}">
                    <i class="fal fa-fw fa-envelope"></i> E-Mail
                </a>
            </li>
        </ul>
    </div>
</div>