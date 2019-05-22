<div class="card mb-3">
    <div class="card-header">
        <b>{{ __('Details') }}</b>
    </div>

    <table class="table">
        <tr>
            <td><b>{{ __('Product nummer') }}</b></td>
            <td>{{ $product->getSku() }}</td>
        </tr>
        <tr>
            <td><b>{{ __('Product groep') }}</b></td>
            <td>{{ $product->getGroup() }}</td>
        </tr>
        @if ($product->getEan())
            <tr>
                <td><b>{{ __('EAN') }}</b></td>
                <td>{{ $product->getEan() }}</td>
            </tr>
        @endif
        <tr>
            <td><b>{{ __('Merk') }}</b></td>
            <td>{{ $product->getBrand() }}</td>
        </tr>
        <tr>
            <td><b>{{ __('Serie') }}</b></td>
            <td>{{ $product->getSeries() }}</td>
        </tr>
        <tr>
            <td><b>{{ __('Type') }}</b></td>
            <td>{{ $product->getType() }}</td>
        </tr>
    </table>
</div>