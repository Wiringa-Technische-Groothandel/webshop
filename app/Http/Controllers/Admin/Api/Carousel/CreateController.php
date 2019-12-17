<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Carousel;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Carousel\CarouselManager;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Admin api create slide controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CreateController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * @var CarouselManager
     */
    protected CarouselManager $carouselManager;

    /**
     * UpdateController constructor.
     *
     * @param Request $request
     * @param LogManager $logManager
     * @param CarouselManager $carouselManager
     */
    public function __construct(Request $request, LogManager $logManager, CarouselManager $carouselManager)
    {
        $this->request = $request;
        $this->logManager = $logManager;
        $this->carouselManager = $carouselManager;
    }

    /**
     * @return JsonResponse
     */
    public function execute(): Response
    {
        try {
            $this->carouselManager->createSlide(
                $this->request->input('title'),
                $this->request->input('caption'),
                $this->request->file('image'),
            );
        } catch (Throwable $throwable) {
            $this->logManager->error($throwable);

            return response()->json(
                [
                    'message' => __('Er is een fout opgetreden tijdens het aanmaken van de slide.'),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'message' => __('De slide is aangemaakt.'),
                'success' => true,
            ]
        );
    }
}
