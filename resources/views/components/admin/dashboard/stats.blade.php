<div class="container-fluid" id="before-content">
    <div class="row mb-3">
        <div class="col-sm-3 animated fadeInRightBig">
            <div class="card">
                <div class="card-body">
                    <table class="header-stat">
                        <tbody>
                        <tr>
                            <td class="icon" rowspan="2"><i class="fal fa-2x fa-book"></i></td>
                            <th class="title">{{ __('Orders') }}</th>
                        </tr>
                        <tr>
                            <td class="value">{{ number_format(\WTG\Models\Order::count())  }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-3 animated fadeInRightBig">
            <div class="card">
                <div class="card-body">
                    <table class="header-stat">
                        <tbody>
                        <tr>
                            <td class="icon" rowspan="2"><i class="fal fa-2x fa-user"></i></td>
                            <th class="title">{{ __('Gebruikers') }}</th>
                        </tr>
                        <tr>
                            <td class="value">{{ number_format(\WTG\Models\Customer::count()) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-3 animated fadeInRightBig">
            <div class="card">
                <div class="card-body">
                    <table class="header-stat">
                        <tbody>
                        <tr>
                            <td class="icon" rowspan="2"><i class="fal fa-2x fa-archive"></i></td>
                            <th class="title">{{ __('Producten') }}</th>
                        </tr>
                        <tr>
                            <td class="value">{{ number_format(\WTG\Models\Product::count()) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-3 animated fadeInRightBig">
            <div class="card">
                <div class="card-body">
                    <table class="header-stat">
                        <tbody>
                        <tr>
                            <td class="icon" rowspan="2"><i class="fal fa-2x fa-percent"></i></td>
                            <th class="title">{{ __('Kortingen') }}</th>
                        </tr>
                        <tr>
                            <td class="value">{{ number_format(\WTG\Models\Discount::count()) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>