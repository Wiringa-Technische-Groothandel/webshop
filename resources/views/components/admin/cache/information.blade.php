<h3>{{ __('Informatie') }}</h3>

<hr />

<div class="row">
    <div class="col-sm-6">
        <table class="table table-striped">
            <thead><tr><th colspan="2" class="text-center">{{ __('Status') }}</th></tr></thead>
            <tbody>
            <tr>
                <td>{{ __('Status') }}</td>
                <td class="text-right"><span class="label label-{{ $opcache_enabled ? 'success' : 'danger' }}">{{ $opcache_enabled ? 'Aan' : 'Uit' }}</span></td>
            </tr>

            <tr>
                <td>{{ __('Cache vol') }}</td>
                <td class="text-right"><span class="label label-{{ !$opcache_full ? 'success' : 'danger' }}">{{ !$opcache_full ? __('Nee') : __('Ja') }}</span></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="col-sm-6">
        <table class="table table-striped">
            <thead><tr><th colspan="2" class="text-center">{{ __('Statistieken') }}</th></tr></thead>
            <tbody>
            <tr>
                <td>{{ __('Cached scripts') }}</td>
                <td class="text-right">{{ $opcache_stats->get('num_cached_scripts') }}</td>
            </tr>

            <tr>
                <td>{{ __('Cached keys') }}</td>
                <td class="text-right">{{ $opcache_stats->get('num_cached_keys') }}</td>
            </tr>

            <tr>
                <td>{{ __('Max cached keys') }}</td>
                <td class="text-right">{{ $opcache_stats->get('max_cached_keys') }}</td>
            </tr>

            <tr>
                <td>{{ __('Cache hits') }}</td>
                <td class="text-right">{{ $opcache_stats->get('hits') }}</td>
            </tr>

            <tr>
                <td>{{ __('Cache misses') }}</td>
                <td class="text-right">{{ $opcache_stats->get('misses') }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>