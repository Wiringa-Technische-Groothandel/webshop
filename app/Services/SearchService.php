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
        $query = $data['query'];

        if (is_numeric($query)) {
            $results = collect([
                Product::findBySku($query)
            ]);
        } else {
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
                $results = $elastic->search(
                    $this->prepareParameters($query, 10000, $filters)
                );

                if ($results['hits']['total'] === 0) {
                    $results = $elastic->search(
                        $this->prepareParameters($query, 10000, $filters, true)
                    );
                }

                return $results;
            });

            $results = $query->get();
        }

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
        $items = Product::search($query, function (Client $elastic, $query, $params) {
            return $elastic->search(
                $this->prepareParameters($query, 5)
            );
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

    /**
     * Prepare search parameters.
     *
     * @param  string  $query
     * @param  int  $size
     * @param  array  $filters
     * @param  bool  $fuzzy
     * @return array
     */
    protected function prepareParameters(string $query, int $size, array $filters = [], bool $fuzzy = false): array
    {
        $escapedQuery = $this->escapeCharacters($query);

        $queryBody = [
            'min_score' => 2.0,
            'size' => $size,
            'query' => [
                'bool' => [
                    'must' => [
                        'multi_match' => [
                            'query' => $escapedQuery,
                            'fields' => ['sku^0.2', 'name^1', 'keywords^0.6', 'ean^0.1', 'group^0.1', 'brand^1'],
                            'fuzziness' => 0
                        ]
                    ]
                ]
            ]
        ];

        if ($filters) {
            $queryBody['query']['bool']['filter'] = $filters;
        }

        if ($fuzzy) {
            $queryBody['query']['bool']['must']['multi_match']['fuzziness'] = 'AUTO';
        }

        $params['body'] = $queryBody;

        return $params;
    }
}