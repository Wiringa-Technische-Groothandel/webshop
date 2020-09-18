<?php

namespace WTG\GraphQL\Queries;

use Illuminate\Support\Collection;
use WTG\Managers\CarouselManager;

class Slides
{
    /**
     * @var CarouselManager
     */
    protected CarouselManager $carouselManager;

    /**
     * Slides constructor.
     *
     * @param CarouselManager $carouselManager
     */
    public function __construct(CarouselManager $carouselManager)
    {
        $this->carouselManager = $carouselManager;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return Collection
     */
    public function __invoke($_, array $args)
    {
        return $this->carouselManager->getOrderedSlides();
    }
}
