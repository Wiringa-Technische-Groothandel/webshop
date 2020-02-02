@extends('layouts.error')

@section('content')
    <div class="card" style="max-width: 400px; margin: 20px auto; background: rgba(255,255,255,.8);">
        <div class="card-body">
            <h4 class="card-title">Foutje, bedankt!</h4>
            <p class="card-text">Er is een fout opgetreden waardoor de pagina niet geladen kon worden. Wij zijn ervan op de hoogte gesteld en zullen proberen om het probleem zo spoedig mogelijk te verhelpen.</p>
            <a href="{{ url()->previous()  }}" class="card-link">Klik hier om terug te gaan naar de vorige pagina</a>
            <h6 class="card-subtitle mt-2 text-muted">Referentie: {{ $referenceId }}</h6>
        </div>
    </div>
@endsection
