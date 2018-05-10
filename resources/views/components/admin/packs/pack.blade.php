<div class="card">
    <div class="card-body">
        <div class="actiepaket">
            <div class="title">
                {{ strlen($pack->getProduct()->getName()) > 40 ? substr($pack->getProduct()->getName(), 0 , 37) . "..." : $pack->getProduct()->getName() }}
            </div>
            <div class="product-list">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Aantal') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($pack->getProducts() as $product)
                        <tr>
                            <td>{{ $product->getProduct()->getSku() }}</td>
                            <td>{{ $product->getAmount() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="actiepaket-actions">
                <a href="{{ route('admin.packs::edit', ['id' =>  $pack->getId()]) }}" class="btn btn-primary btn-block">{{ __('Aanpassen') }}</a>
                <button onclick="showConfirmationModal(this)" data-id="{{ $pack->getId() }}" data-name="{{ $pack->getName() }}" class="btn btn-danger btn-block">{{ __('Verwijderen') }}</button>
            </div>
        </div>
    </div>
</div>