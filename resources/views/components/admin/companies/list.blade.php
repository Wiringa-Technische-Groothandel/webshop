<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-building"></i> {{ __('Bedrijven') }}
        </h3>

        <hr />

        <table id="companies-table" class="table table-hover" style="width:100%"></table>

{{--        <table class="table table-hover">--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th scope="col">#</th>--}}
{{--                <th scope="col"></th>--}}
{{--                <th scope="col"></th>--}}
{{--                <th scope="col"></th>--}}
{{--                <th scope="col"></th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @foreach($companies as $company)--}}
{{--                <tr>--}}
{{--                    <th scope="row">{{ $company->getCustomerNumber() }}</th>--}}
{{--                    <td><a href="{{ route('admin.company.edit', ['id' => $company->getId()]) }}">{{ $company->getName() }}</a></td>--}}
{{--                    <td>{{ $company->getCustomers()->count() }}</td>--}}
{{--                    <td>{{ $company->created_at->format('Y-m-d H:i') }}</td>--}}
{{--                    <td>{{ $company->updated_at->format('Y-m-d H:i') }}</td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}

{{--        <hr />--}}

{{--        {{ $companies->links('pagination::bootstrap-4') }}--}}
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