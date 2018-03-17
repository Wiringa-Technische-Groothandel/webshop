<?php

namespace WTG\Http\Controllers\Catalog;

use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use WTG\Models\Product;
use WTG\Http\Controllers\Controller;
use WTG\Services\SearchService;

/**
 * Assortment controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AssortmentController extends Controller
{
    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * AssortmentController constructor.
     *
     * @param  ViewFactory  $view
     * @param  SearchService  $searchService
     */
    public function __construct(ViewFactory $view, SearchService $searchService)
    {
        parent::__construct($view);

        $this->searchService = $searchService;
    }

    /**
     * Assortment page.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request)
    {
        $page = (int) $request->input('page', 1);

        $results = $this->searchService->listProducts(
            $request->input('brand'),
            $request->input('series'),
            $request->input('type'),
            ($page > 0 ? $page : 1)
        );

        return view('pages.catalog.assortment', compact('results'));
    }
}