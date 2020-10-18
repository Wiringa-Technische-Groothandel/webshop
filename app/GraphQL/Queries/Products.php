<?php

namespace WTG\GraphQL\Queries;

use Illuminate\Pagination\LengthAwarePaginator;
use Nuwave\Lighthouse\Pagination\PaginatorField;
use WTG\Managers\SearchManager;

class Products
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
     */
    public function __invoke($_, array $args)
    {
        [ $products, $brands, $series, $types ] = $this->searchManager->listProducts(
            $args['brand'] ?? null,
            $args['series'] ?? null,
            $args['type'] ?? null
        );

        return [
            'products'      => $products,
            'filters'       => [
                'brands' => $brands,
                'series' => $series,
                'types'  => $types,
            ],
        ];
    }
}
