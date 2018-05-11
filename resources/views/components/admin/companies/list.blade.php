<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-building"></i> {{ __('Bedrijven') }}
        </h3>

        <hr />

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{ __('Naam') }}</th>
                <th scope="col">{{ __('Aantal gebruikers') }}</th>
                <th scope="col">{{ __('Aangemaakt op') }}</th>
                <th scope="col">{{ __('Gewijzigd op') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr>
                    <th scope="row">{{ $company->getCustomerNumber() }}</th>
                    <td><a href="{{ route('admin.company.edit', ['id' => $company->getId()]) }}">{{ $company->getName() }}</a></td>
                    <td>{{ $company->getCustomers()->count() }}</td>
                    <td>{{ $company->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $company->updated_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <hr />

        {{ $companies->links('pagination::bootstrap-4') }}
    </div>
</div>