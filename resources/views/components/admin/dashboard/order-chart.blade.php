<h3>
    <i class="fal fa-fw fa-book"></i> Orders per maand

    @if ($years->isNotEmpty())
        <select id="yearSelect" class="pull-right" onchange="getOrderChartData()">
            @foreach ($years as $year)
                <option value="{{ $year['year'] }}">{{ $year['year'] }}</option>
            @endforeach
        </select>
    @endif
</h3>

<hr />

@if ($years->isNotEmpty())
    <div style="height: 300px;">
        <canvas id="order-chart"></canvas>
    </div>
@else
    <p>{{ __('Geen data om weer te geven.') }}</p>
@endif