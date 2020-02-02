<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web;

use Illuminate\Contracts\View\View;
use Illuminate\View\Factory as ViewFactory;
use WTG\Carousel\CarouselManager;
use WTG\Http\Controllers\Controller;

/**
 * Index controller.
 *
 * @package     WTG\Http
 * @subpackages Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
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
     * @param ViewFactory $view
     * @param CarouselManager $carouselManager
     */
    public function __construct(ViewFactory $view, CarouselManager $carouselManager)
    {
        parent::__construct($view);

        $this->carouselManager = $carouselManager;
    }

    /**
     * Handle the request.
     *
     * @return View
     */
    public function getAction(): View
    {
        $slides = $this->carouselManager->getOrderedSlides();

        return $this->view->make('pages.index', compact('slides'));
    }
}
