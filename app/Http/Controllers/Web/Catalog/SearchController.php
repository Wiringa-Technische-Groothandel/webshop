<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Catalog;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\Factory as ViewFactory;
use WTG\Http\Controllers\Controller;
use WTG\Search\SearchManager;

/**
 * Search controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SearchController extends Controller
{
    protected SearchManager $searchManager;

    /**
     * SearchController constructor.
     *
     * @param ViewFactory $view
     * @param SearchManager $searchManager
     */
    public function __construct(ViewFactory $view, SearchManager $searchManager)
    {
        parent::__construct($view);

        $this->searchManager = $searchManager;
    }

    /**
     * Search page.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getAction(Request $request)
    {
        $page = (int)$request->input('page', 1);
        $searchQuery = $request->input('query');

        if (! $searchQuery) {
            return back();
        }

        $results = $this->searchManager->searchProducts(
            [
                'query'  => $searchQuery,
                'brand'  => $request->input('brand'),
                'series' => $request->input('series'),
                'type'   => $request->input('type'),
            ],
            ($page > 0 ? $page : 1)
        );

        return view('pages.catalog.search', compact('results'));
    }

    /**
     * Search page.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $searchQuery = $request->input('query');

        if (! $searchQuery) {
            return response()->json(
                [
                    'products' => [],
                ]
            );
        }

        return response()->json(
            [
                'products' => $this->searchManager->suggestProducts($searchQuery),
            ]
        );
    }
}
