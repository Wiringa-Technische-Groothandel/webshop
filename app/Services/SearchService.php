<?php

declare(strict_types=1);

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
        $query->where('inactive', false);
        $query->where('blocked', false);

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
                Product::where(function ($builder) use ($query) {
                        return $builder
                            ->orWhere('sku', $query)
                            ->orWhere('ean', $query)
                            ->orWhere('supplier_code', $query);
                    })
                    ->where('inactive', false)
                    ->where('blocked', false)
                    ->first()
            ])->filter();
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

            $results = Product::search($query, function (Client $elastic, $query, $params) use ($page, $filters) {
                $results = $elastic->search(
                    $this->prepareParameters($query, 10000, $filters)
                );

                if ($results['hits']['total'] === 0) {
                    $results = $elastic->search(
                        $this->prepareParameters($query, 10000, $filters, true)
                    );
                }

                return $results;
            })
                ->get()
                ->where('inactive', 0)
                ->where('blocked', 0);
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
        return Product::search($query, function (Client $elastic, $query, $params) {
            return $elastic->search(
                $this->prepareParameters($query, 5)
            );
        })
            ->get()
            ->where('inactive', 0)
            ->where('blocked', 0)
            ->map(function (Product $product) {
                return [
                    'url' => $product->getUrl(),
                    'name' => $product->getName()
                ];
            });
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
        $terms = $this->analyzeQuery($query);
        $must = [];

        if ($terms['required']) {
            $must[] = [
                'multi_match' => [
                    'query' => $terms['required'],
                    'fields' => ['description^1.5', 'brand^1', 'series^1.5', 'type^1', 'supplier_code^1'],
                    'fuzziness' => $fuzzy ? 'AUTO' : 0,
                    'minimum_should_match' => '95%',
                    'operator' => 'and'
                ]
            ];
        }

        if ($terms['any']) {
            $must[] = [
                'multi_match' => [
                    'query' => $terms['any'],
                    'fields' => ['description^1.5', 'brand^1', 'series^1.5', 'type^1', 'supplier_code^1'],
                    'fuzziness' => $fuzzy ? 'AUTO' : 0,
                    'minimum_should_match' => '1',
                    'operator' => 'or'
                ]
            ];
        }

        $queryBody = [
            'min_score' => 1.0,
            'size' => $size,
            'query' => [
                'bool' => [
                    'must' => $must,
                    'should' => $terms['optional'] ? [
                        'multi_match' => [
                            'query' => $terms['optional'],
                            'fields' => ['description^1.5', 'brand^1', 'series^1.5', 'type^1', 'supplier_code^1'],
                            'fuzziness' => $fuzzy ? 'AUTO' : 0,
                            'operator' => 'or'
                        ]
                    ] : []
                ]
            ]
        ];

        if ($filters) {
            $queryBody['query']['bool']['filter'] = $filters;
        }

        $params['body'] = $queryBody;

        return $params;
    }

    /**
     * Analyze the query and split possible lengths.
     *
     * @param string $query
     * @return array
     */
    protected function analyzeQuery(string $query): array
    {
        $terms = explode(' ', $query);
        $requiredTerms = [];
        $anyTerms = [];
        $optionalTerms = [];

        foreach ($terms as $term) {
            if (is_numeric($term) && strlen($term) <= 4) {
                $anyTerms[] = $term . 'mm';
                $anyTerms[] = $term . 'cm';
                $anyTerms[] = $term . 'mtr';
                $anyTerms[] = $term;
            } else {
                $requiredTerms[] = $term;
            }
        }

        return [
            'required' => $this->escapeCharacters(join(' ', $requiredTerms)),
            'any' => $this->escapeCharacters(join(' ', $anyTerms)),
            'optional' => $this->escapeCharacters(join(' ', $optionalTerms))
        ];
    }
}