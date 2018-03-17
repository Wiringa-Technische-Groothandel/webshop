<h3>Information</h3>

<hr />

<div class="row">
    <div class="col-sm-6">
        <table class="table table-striped">
            <thead><tr><th colspan="2" class="text-center">Status</th></tr></thead>
            <tbody>
            <tr>
                <td>Status</td>
                <td class="text-right"><span class="label label-{{ $opcache_enabled ? 'success' : 'danger' }}">{{ $opcache_enabled ? 'Enabled' : 'Disabled' }}</span></td>
            </tr>

            <tr>
                <td>Cache full</td>
                <td class="text-right"><span class="label label-{{ !$opcache_full ? 'success' : 'danger' }}">{{ !$opcache_full ? 'No' : 'Yes' }}</span></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="col-sm-6">
        <table class="table table-striped">
            <thead><tr><th colspan="2" class="text-center">Statistics</th></tr></thead>
            <tbody>
            <tr>
                <td>Cached scripts</td>
                <td class="text-right">{{ $opcache_stats->get('num_cached_scripts') }}</td>
            </tr>

            <tr>
                <td>Cached keys</td>
                <td class="text-right">{{ $opcache_stats->get('num_cached_keys') }}</td>
            </tr>

            <tr>
                <td>Max cached keys</td>
                <td class="text-right">{{ $opcache_stats->get('max_cached_keys') }}</td>
            </tr>

            <tr>
                <td>Cache hits</td>
                <td class="text-right">{{ $opcache_stats->get('hits') }}</td>
            </tr>

            <tr>
                <td>Cache misses</td>
                <td class="text-right">{{ $opcache_stats->get('misses') }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>