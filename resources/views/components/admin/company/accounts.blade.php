<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-user"></i> {{ __('Accounts') }}
        </h3>

        <hr />

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">{{ __('Gebruikersnaam') }}</th>
                <th scope="col" class="d-sm-none d-md-table-cell">{{ __('E-Mail') }}</th>
                <th scope="col">{{ __('Aangemaakt op') }}</th>
                <th scope="col">{{ __('Gewijzigd op') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($company->getCustomers() as $customer)
                <tr>
                    <th scope="row">
                        {{--<a href="{{ route('admin.company.customer.edit', ['company' => $company->getId(), 'customer' => $customer->getId()]) }}">{{ $customer->getUsername() }}</a>--}}
                        {{ $customer->getUsername() }}
                    </th>
                    @if($customer->getContact()->getContactEmail())
                        <td class="d-sm-none d-md-table-cell"><a href="mailto:{{ $customer->getContact()->getContactEmail() }}">{{ $customer->getContact()->getContactEmail() }}</a></td>
                    @else
                        <td class="d-sm-none d-md-table-cell">N.B.</td>
                    @endif
                    <td>{{ $customer->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $customer->updated_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>