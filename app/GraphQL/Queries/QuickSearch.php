<?php

namespace WTG\GraphQL\Queries;

use Illuminate\Support\Collection;
use WTG\Managers\SearchManager;

class QuickSearch
{
    protected SearchManager $searchManager;

    /**
     * QuickSearch constructor.
     *
     * @param SearchManager $searchManager
     */
    public function __construct(SearchManager $searchManager)
    {
        $this->searchManager = $searchManager;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return Collection
     */
    public function __invoke($_, array $args)
    {
        return $this->searchManager->suggestProducts($args['term']);
    }
}
