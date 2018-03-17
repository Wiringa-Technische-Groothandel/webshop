<?php

namespace WTG\Http\Controllers\Catalog;

use WTG\Models\Product;
use Illuminate\Http\Request;
use WTG\Services\SearchService;
use WTG\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;

/**
 * Search controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SearchController extends Controller
{
    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * SearchController constructor.
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
     * Search page.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function getAction(Request $request)
    {
        $page = (int) $request->input('page', 1);
        $searchQuery = $request->input('query');

        if (! $searchQuery) {
            return back();
        }

        $results = $this->searchService->searchProducts([
            'query'     => $searchQuery,
            'brand'     => $request->input('brand'),
            'series'     => $request->input('series'),
            'type'     => $request->input('type'),
        ], ($page > 0 ? $page : 1));

        return view('pages.catalog.search', compact('results'));
    }

    /**
     * Search page.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function postAction(Request $request)
    {
        $searchQuery = $request->input('query');

        if (! $searchQuery) {
            return back();
        }

        /** @var SearchService $service */
        $service = app()->make(SearchService::class);
        $results = $service->suggestProducts($searchQuery);

        return response()->json([
            'products' => $results
        ]);
    }
}