<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Catalog;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\Factory as ViewFactory;
use WTG\Http\Controllers\Controller;
use WTG\Services\SearchService;

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
     * @param ViewFactory $view
     * @param SearchService $searchService
     */
    public function __construct(ViewFactory $view, SearchService $searchService)
    {
        parent::__construct($view);

        $this->searchService = $searchService;
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

        $results = $this->searchService->searchProducts(
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

        /** @var SearchService $service */
        $service = app(SearchService::class);

        return response()->json(
            [
                'products' => $service->suggestProducts($searchQuery),
            ]
        );
    }
}
