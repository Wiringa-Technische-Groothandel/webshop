<?php

namespace WTG\Services;

use WTG\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Search service.
 *
 * @package     WTG
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SearchService
{
    /**
     * Search for products.
     *
     * @param  array  $data
     * @param  int  $page
     * @return Collection
     */
    public function searchProducts(array $data, int $page = 1): Collection
    {
        $query  = $data['query'];
        $brand  = $data['brand'] ?? false;
        $series = $data['series'] ?? false;
        $type   = $data['type'] ?? false;

        $query = Product::search($query, function ($elastic, $query, $params) {
            $params['body']['size'] = 10000;

            return $elastic->search($params);
        });

        if ($brand) {
            $query->where('brand', $brand);

            if ($series) {
                $query->where('series', $series);

                if ($type) {
                    $query->where('type', $type);
                }
            }
        }

        $results = $query->get();
        $paginator = new LengthAwarePaginator($results->forPage($page, 10), $results->count(), 10, $page);
        $paginator->withPath('search');
        $paginator->appends($data);

        return collect([
            'products' => $paginator,
            'brands' => $results->pluck('brand')->unique()->sort(),
            'series' => $results->pluck('series')->unique()->sort(),
            'types' => $results->pluck('type')->unique()->sort(),
        ]);
    }

    /**
     * Quicksearch items.
     *
     * @param  string  $query
     * @return Collection
     */
    public function suggestProducts(string $query): Collection
    {
        /** @var Collection $items */
        $items = Product::search($query, function ($elastic, $query, $params) {
            $params['body']['size'] = 5;

            return $elastic->search($params);
        })->get();

        $items = $items->map(function (Product $product) {
            return [
                'url' => route('catalog.product', [ 'sku' => $product->getSku() ]),
                'name' => $product->getName()
            ];
        });

        return $items;
    }
}