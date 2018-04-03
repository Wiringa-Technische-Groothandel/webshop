@extends('master', ['pagetitle' => 'Home', 'hideTitle' => true])

@section('title')
    <h1>Welkom op de website van Wiringa Technische Groothandel</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-5">
            <div id="slideshow" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @for($i=0; $i < count($carouselSlides); $i++)
                        <li data-target="#slideshow" data-slide-to="{{ $i }}" class="{{ ($i === 0 ? 'active' : '') }}"></li>
                    @endfor
                </ol>

                <div class="carousel-inner">
                    <?php $count = 1; ?>
                    @foreach ($carouselSlides as $slide)
                        <div class="item {{ ($count === 1 ? 'active' : '') }}">
                            <img src="/img/carousel/{{ $slide->Image }}" alt="{{ $slide->Image }}" style="height: 300px">
                            <div class="carousel-caption">
                                <h3>{{ $slide->Title }}</h3>
                                <p>{{ $slide->Caption }}</p>
                            </div>
                        </div>
                        <?php $count++; ?>
                    @endforeach
                </div>

                <a class="carousel-control left" href="#slideshow" data-slide="prev"><span class="icon-prev"></span></a>
                <a class="carousel-control right" href="#slideshow" data-slide="next"><span class="icon-next"></span></a>
            </div>
        </div>

        <div class="col-lg-7">
            @if(Auth::check())
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Nieuws</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                            {!! $news->content !!}
                        </p>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Nieuws</h3>
                            </div>
                            <div class="panel-body">
                                <p>
                                    {!! $news->content !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="well well-sm text-center">
                            <h3>Klant worden?</h3>
                            <a class="btn btn-block btn-success" href="/register">Ja, ik wil klant worden!</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
