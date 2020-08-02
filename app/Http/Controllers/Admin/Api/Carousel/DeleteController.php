<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Carousel;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Managers\CarouselManager;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Admin api delete slide controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DeleteController extends Controller
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
        $slideId = (int)$this->request->input('id');

        try {
            $slide = $this->carouselManager->findSlide($slideId);

            $this->carouselManager->deleteSlide($slide);
        } catch (Throwable $throwable) {
            $this->logManager->error($throwable);

            return response()->json(
                [
                    'message' => __('Er is een fout opgetreden tijdens het verwijderen van de slide.'),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'message' => __('De slide is verwijderd.'),
                'success' => true,
            ]
        );
    }
}
