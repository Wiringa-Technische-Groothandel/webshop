<div class="card">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-building"></i> {{ __('Gegevens') }}
            <a class="btn btn-primary toggle-edit float-right" data-toggle="#save">
                <i class="fal fa-fw fa-pencil"></i> {{ __('Aanpassen') }}
            </a>
        </h3>

        <hr />

        <form method="post">
            {{ csrf_field() }}

            <dl class="row">
                <dt class="col-sm-4">{{ __('Naam') }}</dt>
                <dd class="col-sm-8 input-switch" data-class="form-control" data-name="name">
                    {{ $company->getName() }}
                </dd>

                <dt class="col-sm-4">{{ __('Klantnummer') }}</dt>
                <dd class="col-sm-8 input-switch" data-class="form-control" data-name="customer-number">
                    {{ $company->getCustomerNumber() }}
                </dd>

                <dt class="col-sm-4">{{ __('Straat') }}</dt>
                <dd class="col-sm-8 input-switch" data-class="form-control" data-name="street">
                    {{ $company->getStreet() }}
                </dd>

                <dt class="col-sm-4">{{ __('Plaats') }}</dt>
                <dd class="col-sm-8 input-switch" data-class="form-control" data-name="city">
                    {{ $company->getCity() }}
                </dd>

                <dt class="col-sm-4">{{ __('Postcode') }}</dt>
                <dd class="col-sm-8 input-switch" data-class="form-control" data-name="postcode">
                    {{ $company->getPostcode() }}
                </dd>

                <dt class="col-sm-4">{{ __('Is actief') }}</dt>
                <dd class="col-sm-8 input-switch" data-checked="{{ $company->getActive() }}" data-type="checkbox" data-name="active">
                    {{ $company->getActive() ? 'Ja' : 'Nee' }}
                </dd>
            </dl>

            <button class="btn btn-success float-right" style="display: none;" id="save">{{ __('Opslaan') }}</button>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $('.toggle-edit').on('click', function () {
            $(this).hide();

            $($(this).data('toggle')).show();

            $('.input-switch').each(function () {
                let $this = $(this);
                let value = this.textContent.trim();
                let name = $this.data('name');
                let classes = $this.data('class');
                let type = $this.data('type') ? $(this).data('type') : 'text';
                let $input = $('<input />');

                $input.attr('type', type)
                    .attr('value', value)
                    .attr('placeholder', value)
                    .attr('class', classes)
                    .attr('name', name);

                if (type === 'checkbox' || type === 'radio') {
                    $input.prop('checked', $this.data('checked'))
                }

                $this.html($input);
            });
        });
    </script>
@endpush