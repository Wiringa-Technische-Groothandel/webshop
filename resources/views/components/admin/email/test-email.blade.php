<div class="card">
    <div class="card-body">
        <h3><i class="fal fa-fw fa-envelope"></i> {{ __('Verstuur test email') }}</h3>

        <hr />

        <form action="{{ route('admin.email.test') }}" method="post">
            {{ csrf_field() }}

            <div class="form-group">
                <label>{{ __('E-Mail') }}</label>
                <input class="form-control" type="email" name="email" placeholder="{{ __('E-Mail') }}" required />
            </div>

            <button class="btn btn-raised btn-success" type="submit">{{ __('Verstuur test mail') }}</button>
        </form>
    </div>
</div>
