<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-building"></i> {{ __('Bedrijven') }}
        </h3>

        <hr />

        <table id="companies-table" class="table table-hover" style="width:100%"></table>
    </div>
</div>

@push('scripts')
    <script>
        var companies = {!! $companies !!};
        var table = $('#companies-table').DataTable({
            data: companies,
            columns: [
                { title: "#", data: 'customer_number' },
                { title: "{{ __('Naam') }}", data: 'name' },
                { title: "{{ __('Aantal gebruikers') }}", data: 'account_count' },
                { title: "{{ __('Aangemaakt op') }}", data: 'created_at' },
                { title: "{{ __('Gewijzigd op') }}", data: 'updated_at' }
            ]
        });

        $('#companies-table_length').hide();

        $('#companies-table tbody').on('click', 'tr', function () {
            var data = table.row(this).data();

            window.location.href = data.editUrl;
        } );
    </script>
@endpush