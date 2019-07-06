<div class="card mb-3">
    <div class="card-body">
        <div class="pack">
            <div class="title">
                <h5 title="{{ $pack->getProduct()->getName() }}">{{ strlen($pack->getProduct()->getName()) > 40 ? substr($pack->getProduct()->getName(), 0 , 37) . "..." : $pack->getProduct()->getName() }}</h5>
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
                    <form method="post" class="m-0" onsubmit="return confirm('Weet u zeker dat u actiepakket \'{{ $pack->getProduct()->getName() }}\' wilt verwijderen?');">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <input type="hidden" name="pack_id" value="{{ $pack->getId() }}" />

                        <button class="btn btn-danger btn-block">{{ __('Verwijderen') }}</button>
                    </form>
                </div>

                <div class="col">
                    <a href="{{ route('admin.pack.edit', ['id' =>  $pack->getId()]) }}"
                       class="btn btn-primary btn-block">{{ __('Aanpassen') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>