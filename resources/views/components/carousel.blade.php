<div id="home-carousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @foreach ($slides as $slide)
            <li data-target="#home-carousel" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
        @endforeach
    </ol>

    <div class="carousel-inner">
        @foreach ($slides as $slide)
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                <div class="d-block w-100 carousel-image" style="background: url('{{ asset('storage/uploads/images/carousel/' . $slide->getImage()) }}') center no-repeat; background-size: contain;"></div>
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ $slide->getTitle() }}</h5>
                    <p>{{ $slide->getCaption() }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <a class="carousel-control-prev" href="#home-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Vorige</span>
    </a>

    <a class="carousel-control-next" href="#home-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Volgende</span>
    </a>
</div>
