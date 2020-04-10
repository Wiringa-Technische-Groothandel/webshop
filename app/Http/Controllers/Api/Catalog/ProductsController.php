<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Catalog;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Api\Controller;
use WTG\Search\SearchManager;

/**
 * API Catalog products list controller.
 *
 * @package     WTG\Http\Controllers\Web\Api\CMS
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductsController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var SearchManager
     */
    protected SearchManager $searchManager;

    /**
     * BlocksController constructor.
     *
     * @param Request $request
     * @param SearchManager $searchManager
     */
    public function __construct(Request $request, SearchManager $searchManager)
    {
        $this->request = $request;
        $this->searchManager = $searchManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $page = (int)$this->request->input('page', 1);

        $results = $this->searchManager->listProducts(
            $this->request->input('brand'),
            $this->request->input('series'),
            $this->request->input('type'),
            ($page > 0 ? $page : 1)
        );

        return response()->json($results);
    }
}
