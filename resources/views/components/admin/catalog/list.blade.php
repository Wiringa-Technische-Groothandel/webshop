<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-list"></i> {{ __('Producten') }}
        </h3>

        <hr />

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">{{ __('SKU') }}</th>
                <th scope="col">{{ __('Groep') }}</th>
                <th scope="col">{{ __('Naam') }}</th>
                <th scope="col">{{ __('Aangemaakt op') }}</th>
                <th scope="col">{{ __('Gewijzigd op') }}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <th scope="row">{{ $product->getSku() }}</th>
                        <td>{{ $product->getGroup() }}</td>
                        <td>{{ $product->getName() }}</td>
                        <td>{{ $product->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $product->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr />

        {{ $products->links('pagination::bootstrap-4') }}
    </div>
</div>