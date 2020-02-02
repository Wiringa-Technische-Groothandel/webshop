<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Carousel;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use WTG\Carousel\CarouselManager;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Admin api fetch slides controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
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
     * @return JsonResponse
     */
    public function execute(): Response
    {
        return response()->json(
            [
                'slides' => $this->carouselManager->getOrderedSlides(),
            ]
        );
    }
}
