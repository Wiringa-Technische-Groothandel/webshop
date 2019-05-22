<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-chart-bar"></i> Mailgun stats
        </h3>

        <hr />

        <select id="period-selector" class="custom-select w-auto">
            <option value="6h">6 Uur</option>
            <option value="12h">12 Uur</option>
            <option value="24h">1 Dag</option>
            <option value="7d">1 Week</option>
            <option value="31d" selected>1 Maand</option>
            <option value="3m">3 Maanden</option>
            <option value="6m">6 Maanden</option>
            <option value="12m">1 Jaar</option>
        </select>


        <div style="height: 300px;">
            <canvas id="mailgun-stats-chart"></canvas>
        </div>
    </div>
</div>
