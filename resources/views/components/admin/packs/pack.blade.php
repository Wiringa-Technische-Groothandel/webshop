<div class="card">
    <div class="card-body">
        <div class="actiepaket">
            <div class="title">
                <h5>{{ strlen($pack->getProduct()->getName()) > 40 ? substr($pack->getProduct()->getName(), 0 , 37) . "..." : $pack->getProduct()->getName() }}</h5>
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
                    @foreach($pack->getItems() as $item)
                        <tr>
                            <td>{{ $item->getProduct()->getSku() }}</td>
                            <td>{{ $item->getAmount() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col">
                    <button onclick="showConfirmationModal(this)" data-id="{{ $pack->getId() }}"
                            data-name="{{ $pack->getProduct()->getName() }}"
                            class="btn btn-danger btn-block">{{ __('Verwijderen') }}</button>
                </div>

                <div class="col">
                    <a href="{{ route('admin.packs.edit', ['id' =>  $pack->getId()]) }}"
                       class="btn btn-primary btn-block">{{ __('Aanpassen') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>