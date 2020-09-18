<?php

namespace WTG\GraphQL\Queries;

use Illuminate\Pagination\LengthAwarePaginator;
use Nuwave\Lighthouse\Pagination\PaginatorField;
use WTG\Managers\SearchManager;

class Search
{
    protected SearchManager $searchManager;
    protected PaginatorField $paginatorField;

    /**
     * Search constructor.
     *
     * @param SearchManager $searchManager
     * @param PaginatorField $paginatorField
     */
    public function __construct(SearchManager $searchManager, PaginatorField $paginatorField)
    {
        $this->searchManager = $searchManager;
        $this->paginatorField = $paginatorField;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return array
     */
    public function __invoke($_, array $args)
    {
        $page = $args['page'] ?? 1;
        $products = $this->searchManager->searchProducts(
            [
                'query' => $args['term'],
            ]
        );

        $paginator = new LengthAwarePaginator($products->forPage($page, 10), $products->count(), 10, $page);

        return [
            'products'      => $this->paginatorField->dataResolver($paginator),
            'filters'        => [
                'brands' => $products->pluck('brand')->unique()->sort(),
                'series' => $products->pluck('series')->unique()->sort(),
                'types'  => $products->pluck('type')->unique()->sort(),
            ],
            'paginatorInfo' => $this->paginatorField->paginatorInfoResolver($paginator),
        ];
    }
}
