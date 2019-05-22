<h4>{{ __("Merk") }}</h4>

<div class="row">
    <div class="col-12">
        <select name="brand" class="custom-select w-100" onchange="form.type.disabled = form.series.disabled = true; this.disabled = !this.value; form.submit()">
            <option value="">
                {{ request('brand') ? __('--- Verwijder selectie ---') : __("--- Selecteer een merk ---") }}
            </option>

            @foreach ($results->get('brands') as $brand)
                <option {{ $brand === request('brand') ? 'selected' : '' }}>{{ $brand }}</option>
            @endforeach
        </select>
    </div>
</div>

<hr />

<h4>{{ __("Serie") }}</h4>

<div class="row">
    <div class="col-12">
        @if (request('brand'))
            <select name="series" class="custom-select w-100" onchange="form.type.disabled = true; this.disabled = !this.value; form.submit()">
                <option value="">{{ request('series') ? __('--- Verwijder selectie ---') : __("--- Selecteer een serie ---") }}</option>

                @foreach ($results->get('series') as $series)
                    <option {{ $series === request('series') ? 'selected' : '' }}>{{ $series }}</option>
                @endforeach
            </select>
        @else
            <select name="series" class="custom-select w-100" disabled>
                <option value="">{{ __("--- Selecteer eerst een merk ---") }}</option>
            </select>
        @endif
    </div>
</div>

<hr />

<h4>{{ __("Type") }}</h4>

<div class="row">
    <div class="col-12">
        @if (request('brand') && request('series'))
            <select name="type" class="custom-select w-100" onchange="this.disabled = !this.value; form.submit()">
                <option value="">{{ request('type') ? __('--- Verwijder selectie ---') : __("--- Selecteer een type ---") }}</option>

                @foreach ($results->get('types') as $type)
                    <option {{ $type === request('type') ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        @else
            <select name="type" class="custom-select w-100" disabled>
                <option value="">{{ __("--- Selecteer eerst een serie ---") }}</option>
            </select>
        @endif
    </div>
</div>