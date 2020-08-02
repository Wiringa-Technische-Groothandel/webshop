<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Catalog;

use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use WTG\Http\Controllers\Controller;
use WTG\Managers\SearchManager;

/**
 * Assortment controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AssortmentController extends Controller
{
    protected SearchManager $searchManager;

    /**
     * AssortmentController constructor.
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
     * Assortment page.
     *
     * @param Request $request
     * @return View
     */
    public function getAction(Request $request)
    {
        $page = (int)$request->input('page', 1);

        $results = $this->searchManager->listProducts(
            $request->input('brand'),
            $request->input('series'),
            $request->input('type'),
            ($page > 0 ? $page : 1)
        );

        return view('pages.catalog.assortment', compact('results'));
    }
}
