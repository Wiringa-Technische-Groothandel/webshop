<div class="modal fade" id="addProductPack" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" class="form form-horizontal">
                {{ csrf_field() }}
                {{ method_field('put') }}

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Actiepakket toevoegen') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">{{ __('Artikelnummer') }}*</label>
                        <input oninput="getProductName(this)" class="form-control" maxlength="100" value="{{ old('product') }}"
                               placeholder="{{ __('Artikelnummer') }}" type="text" name="product" required>
                    </div>

                    <div class="form-group">
                        <label for="name">{{ __('Naam') }}</label>
                        <input class="form-control" type="text" id="name" disabled>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-primary">{{ __('Toevoegen') }}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Sluiten') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        /**
         * Get the product name.
         *
         * @param input
         * @returns {Promise<void>}
         */
        async function getProductName(input) {
            let sku = input.value;

            if (sku.length !== 8) {
                return;
            }

            let response = await axios.get('/api/product/' + sku);

            $('#name').val(response.data.product.name);
        }
    </script>
@endpush