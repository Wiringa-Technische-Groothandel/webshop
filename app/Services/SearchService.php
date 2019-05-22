<?php

namespace WTG\Services;

use Elasticsearch\Client;
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
     * Product listing.
     *
     * @param  null|string  $brand
     * @param  null|string  $series
     * @param  null|string  $type
     * @param  int  $page
     * @return Collection
     */
    public function listProducts(?string $brand = null, ?string $series = null, ?string $type = null, int $page = 1): Collection
    {
        $query = Product::query();

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
        $paginator->withPath('assortment');
        $paginator->appends([
            'brand' => $brand,
            'series' => $series,
            'type' => $type
        ]);

        return collect([
            'products' => $paginator,
            'brands' => $results->pluck('brand')->unique()->sort(),
            'series' => $results->pluck('series')->unique()->sort(),
            'types' => $results->pluck('type')->unique()->sort(),
        ]);
    }

    /**
     * Search for products.
     *
     * @param  array  $data
     * @param  int  $page
     * @return Collection
     */
    public function searchProducts(array $data, int $page = 1): Collection
    {
        $query   = $data['query'];
        $brand   = $data['brand'] ?? false;
        $series  = $data['series'] ?? false;
        $type    = $data['type'] ?? false;
        $filters = [];

        if ($brand) {
            $filters[] = ['term' => ['brand.keyword' => $brand]];

            if ($series) {
                $filters[] = ['term' => ['series.keyword' => $series]];

                if ($type) {
                    $filters[] = ['term' => ['type.keyword' => $type]];
                }
            }
        }
        
        $query = Product::search($query, function (Client $elastic, $query, $params) use ($page, $filters) {
            $escapedQuery = $this->escapeCharacters($query);

            $queryBody = [
                'size' => 10000,
                'query' => [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' => $escapedQuery,
                                'fields' => ['sku^0.5', 'name^2', 'keywords^1', 'ean^0.3', 'group^0.3'],
                                'fuzziness' => 0
                            ]
                        ]
                    ]
                ]
            ];

            if ($filters) {
                $queryBody['query']['bool']['filter'] = $filters;
            }

            $params['body'] = $queryBody;

            $results = $elastic->search($params);

            if ($results['hits']['total'] === 0) {
                $params['body']['query']['bool']['must']['multi_match']['fuzziness'] = 'AUTO';

                $results = $elastic->search($params);
            }

            return $results;
        });

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
                'url' => $product->getUrl(),
                'name' => $product->getName()
            ];
        });

        return $items;
    }

    /**
     * Escape reserved Elasticsearch characters.
     *
     * @param  string  $query
     * @return string  The escaped query
     */
    protected function escapeCharacters(string $query)
    {
        $reserved = [
            "\\", "+", "-", "=", "&&", "||", "!", "(", ")", "{",
            "}", "[", "]", "^", "\"", "~", "*", "?", ":", "/"
        ];

        foreach ($reserved as $char) {
            $query = str_replace($char, "\\$char", $query);
        }

        foreach (["<", ">"] as $char) {
            $query = str_replace($char, "", $query);
        }

        return $query;
    }
}