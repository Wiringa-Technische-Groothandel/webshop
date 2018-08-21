<div class="card mb-3">
    <div class="card-header">
        <b>{{ __('Extra omschrijving') }}</b>
    </div>
    <div class="card-body">
        <div class="card-text">
            {!! $product->getDescription()->getValue() !!}
        </div>
    </div>
</div>