<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-list"></i> {{ __('Producten') }}
        </h3>

        <hr />

        <table id="products-table" class="table table-hover" style="width: 100%"></table>
    </div>
</div>

@push('scripts')
    <script async defer>
        var products = {!! $products !!};
        var table = $('#products-table').DataTable({
            data: products,
            columns: [
                { title: "{{ __('SKU') }}", data: 'sku' },
                { title: "{{ __('Groep') }}", data: 'group' },
                { title: "{{ __('Naam') }}", data: 'name' },
                { title: "{{ __('Aangemaakt op') }}", data: 'created_at' },
                { title: "{{ __('Gewijzigd op') }}", data: 'updated_at' }
            ]
        });

        $('#products-table_length').hide();

        $('#products-table tbody').on('click', 'tr', function () {
            var data = table.row(this).data();

            $('#product-sync-input').val(data.sku);
        } );
    </script>
@endpush