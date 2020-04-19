<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Catalog;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Foundation\Exceptions\MissingInputException;
use WTG\Http\Controllers\Api\Controller;
use WTG\Search\SearchFilter;
use WTG\Search\SearchManager;

/**
 * Search api controller.
 *
 * @package     WTG\Http\Controllers\Api\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SearchController extends Controller
{
    protected SearchManager $searchManager;
    protected Request $request;

    /**
     * SearchController constructor.
     *
     * @param Request $request
     * @param SearchManager $searchManager
     */
    public function __construct(Request $request, SearchManager $searchManager)
    {
        $this->searchManager = $searchManager;
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        try {
            $searchResults = $this->searchManager->searchProducts(
                $this->buildSearchFilter(),
                (int)$this->request->input('page', 1)
            );
        } catch (MissingInputException $e) {
            return response()->json(
                [
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            $searchResults->toArray()
        );
    }

    /**
     * @return SearchFilter
     * @throws MissingInputException
     */
    protected function buildSearchFilter(): SearchFilter
    {
        $searchFilter = new SearchFilter();

        if (! $this->request->has('query')) {
            throw new MissingInputException('query');
        }

        $searchFilter->setQuery($this->request->input('query'));
        $searchFilter->setBrand($this->request->input('brand', ''));
        $searchFilter->setSeries($this->request->input('series', ''));
        $searchFilter->setType($this->request->input('type', ''));

        return $searchFilter;
    }
}
