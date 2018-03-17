<?php

namespace WTG\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CarouselServiceContract;

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
     * @var CarouselServiceContract
     */
    protected $carouselService;

    /**
     * IndexController constructor.
     *
     * @param  ViewFactory  $view
     * @param  CarouselServiceContract  $carouselService
     */
    public function __construct(ViewFactory $view, CarouselServiceContract $carouselService)
    {
        parent::__construct($view);

        $this->carouselService = $carouselService;
    }

    /**
     * Handle the request.
     *
     * @return View
     */
    public function getAction(): View
    {
        $slides = $this->carouselService->getOrderedSlides();

        return $this->view->make('pages.index', compact('slides'));
    }
}
