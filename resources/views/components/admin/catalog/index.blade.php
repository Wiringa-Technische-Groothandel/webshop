<div class="card mb-3">
    <div class="card-body">
        <h3>
            <i class="fal fa-fw fa-sync"></i> {{ __('Opnieuw indexeren') }}
        </h3>

        <hr>

        <div class="alert alert-warning">
            <i class="fas fa-fw fa-exclamation-triangle"></i>
            {{ __('Tijdens het indexeren zal de zoekfunctie tijdelijk niet werken.') }}
        </div>

        <form action="{{ route('admin.catalog.reindex') }}" method="post">
            {{ csrf_field() }}

            <button class="btn btn-raised btn-success">
                {{ __('Indexatie starten') }}
            </button>
        </form>
    </div>
</div>