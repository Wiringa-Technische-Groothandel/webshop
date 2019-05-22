<div class="card mb-3">
    <div class="card-header">
        <b>{{ __('Aktiepakket inhoud') }}</b>
    </div>

    <div class="card-body">
        <ul class="mb-0">
            @foreach($product->getPack()->getItems() as $item)
                <li>{{ $item->getAmount() }}x <a href="{{ $item->getProduct()->getUrl() }}">{{ $item->getProduct()->getName() }}</a></li>
            @endforeach
        </ul>
    </div>
</div>