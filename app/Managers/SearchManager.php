<?php

declare(strict_types=1);

namespace WTG\Managers;

use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use WTG\Models\Product;

/**
 * Search manager.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SearchManager
{
    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * SearchManager constructor.
     *
     * @param ProductManager $productManager
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * Product listing.
     *
     * @param null|string $brand
     * @param null|string $series
     * @param null|string $type
     * @return array
     */
    public function listProducts(
        ?string $brand = null,
        ?string $series = null,
        ?string $type = null
    ): array {
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

        $results = $query->get(['id', 'brand', 'series', 'type']);

        return [
            $results->pluck('id')->all(),
            $results->pluck('brand')->unique()->sort(),
            $results->pluck('series')->unique()->sort(),
            $results->pluck('type')->unique()->sort(),
        ];
    }

    /**
     * Search for products.
     *
     * @param array $data
     * @return Collection
     */
    public function searchProducts(array $data): Collection
    {
        $query = $data['query'];

        if (is_numeric($query)) {
            $results = collect(
                [
                    Product::where(
                        function ($builder) use ($query) {
                            return $builder
                                ->orWhere('sku', $query)
                                ->orWhere('ean', $query)
                                ->orWhere('supplier_code', $query);
                        }
                    )
                        ->where('inactive', 0)
                        ->where('blocked', 0)
                        ->first(),
                ]
            )->filter();
        } else {
            $brand = $data['brand'] ?? false;
            $series = $data['series'] ?? false;
            $type = $data['type'] ?? false;
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

            $results = Product::search(
                $query,
                function (Client $elastic, $query, $params) use ($filters) {
                    $results = $elastic->search(
                        $this->prepareParameters($query, 10000, $filters)
                    );

                    if ($results['hits']['total'] === 0) {
                        $results = $elastic->search(
                            $this->prepareParameters($query, 10000, $filters, true)
                        );
                    }

                    return $results;
                }
            )
                ->query(
                    function (Builder $query) {
                        $query
                            ->where('inactive', 0)
                            ->where('blocked', 0);
                    }
                )
                ->get();
        }

//        $paginator = new LengthAwarePaginator($results->forPage($page, 10), $results->count(), 10, $page);
//        $paginator->withPath('search');
//        $paginator->appends($data);

        return $results;
    }

    /**
     * Prepare search parameters.
     *
     * @param string $query
     * @param int $size
     * @param array $filters
     * @param bool $fuzzy
     * @return array
     */
    protected function prepareParameters(string $query, int $size, array $filters = [], bool $fuzzy = false): array
    {
        $terms = $this->analyzeQuery($query);
        $must = [];

        if ($terms['required']) {
            $must[] = [
                'multi_match' => [
                    'query'                => $terms['required'],
                    'fields'               => [
                        'description^1.5',
                        'brand^1',
                        'series^1.5',
                        'type^1',
                        'supplier_code^1',
                        'long_description^0.7',
                    ],
                    'fuzziness'            => $fuzzy ? 'AUTO' : 0,
                    'minimum_should_match' => '95%',
                    'operator'             => 'and',
                ],
            ];
        }

        if ($terms['any']) {
            $must[] = [
                'multi_match' => [
                    'query'                => $terms['any'],
                    'fields'               => [
                        'description^1.5',
                        'brand^1',
                        'series^1.5',
                        'type^1',
                        'supplier_code^1',
                        'long_description^0.7',
                    ],
                    'fuzziness'            => $fuzzy ? 'AUTO' : 0,
                    'minimum_should_match' => '1',
                    'operator'             => 'or',
                ],
            ];
        }

        $queryBody = [
            'min_score' => 1.0,
            'size'      => $size,
            'query'     => [
                'bool' => [
                    'must'   => $must,
                    'should' => $terms['optional'] ? [
                        'multi_match' => [
                            'query'     => $terms['optional'],
                            'fields'    => [
                                'description^1.5',
                                'brand^1',
                                'series^1.5',
                                'type^1',
                                'supplier_code^1',
                                'long_description^0.7',
                            ],
                            'fuzziness' => $fuzzy ? 'AUTO' : 0,
                            'operator'  => 'or',
                        ],
                    ] : [],
                ],
            ],
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
            'required' => join(' ', $requiredTerms),
            'any'      => join(' ', $anyTerms),
            'optional' => join(' ', $optionalTerms),
        ];
    }

    /**
     * Quicksearch items.
     *
     * @param string $query
     * @return Collection
     */
    public function suggestProducts(string $query): Collection
    {
        return Product::search(
            $query,
            function (Client $elastic, $query, $params) {
                return $elastic->search(
                    $this->prepareParameters($query, 5)
                );
            }
        )
            ->get()
            ->where('inactive', 0)
            ->where('blocked', 0);
    }
}
