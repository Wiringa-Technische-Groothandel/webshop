<div class="container-fluid" id="before-content">
    <div class="row">
        <div class="col-sm-3 animated fadeInRightBig">
            <table class="header-stat">
                <tbody>
                <tr>
                    <td class="icon" rowspan="2"><i class="fal fa-book"></i></td>
                    <th class="title">Orders</th>
                </tr>
                <tr>
                    <td class="value">{{ number_format(\WTG\Models\Order::count())  }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-sm-3 animated fadeInRightBig">
            <table class="header-stat">
                <tbody>
                <tr>
                    <td class="icon" rowspan="2"><i class="fal fa-user"></i></td>
                    <th class="title">Gebruikers</th>
                </tr>
                <tr>
                    <td class="value">{{ number_format(\WTG\Models\Customer::count()) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-sm-3 animated fadeInRightBig">
            <table class="header-stat">
                <tbody>
                <tr>
                    <td class="icon" rowspan="2"><i class="fal fa-archive"></i></td>
                    <th class="title">Producten</th>
                </tr>
                <tr>
                    <td class="value">{{ number_format(\WTG\Models\Product::count()) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-sm-3 animated fadeInRightBig">
            <table class="header-stat">
                <tbody>
                <tr>
                    <td class="icon" rowspan="2"><i class="fal fa-percent"></i></td>
                    <th class="title">Kortingen</th>
                </tr>
                <tr>
                    <td class="value">{{ number_format(\WTG\Models\Discount::count()) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>