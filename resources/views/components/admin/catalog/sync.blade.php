<div class="card mb-3">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-sync"></i> {{ __('Update product') }}
        </h3>

        <hr>

        <form action="{{ route('admin.catalog.sync') }}" method="post">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="product-sync-input" class="control-label">{{ __('Productnummer') }}</label>
                <input id="product-sync-input" name="sku" class="form-control" type="number"  />
            </div>

            <button class="btn btn-raised btn-success">
                {{ __('Sync') }}
            </button>
        </form>
    </div>
</div>