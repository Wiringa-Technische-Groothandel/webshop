<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Carousel;

use Symfony\Component\HttpFoundation\Response;
use WTG\Carousel\CarouselManager;
use WTG\Http\Controllers\Controller;

/**
 * Carousel api index controller.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var CarouselManager
     */
    protected CarouselManager $carouselManager;

    /**
     * IndexController constructor.
     *
     * @param CarouselManager $carouselManager
     */
    public function __construct(CarouselManager $carouselManager)
    {
        $this->carouselManager = $carouselManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            $this->carouselManager->getOrderedSlides()->all()
        );
    }
}
