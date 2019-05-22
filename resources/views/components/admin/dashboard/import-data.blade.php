<h3>
    <i class="fal fa-fw fa-download"></i> {{ __('Import data') }}
</h3>

<hr />

<h5>{{ __('Product import') }}</h5>

<dl>
    <dt>{{ __('Bestand') }}</dt>
    <dd>{{ \WTG\Models\ImportData::key('last_assortment_file')->first()->getValue() }}</dd>

    <dt>{{ __('Datum') }}</dt>
    <dd>{{ \WTG\Models\ImportData::key('last_assortment_run_time')->first()->getValue() }}</dd>
</dl>