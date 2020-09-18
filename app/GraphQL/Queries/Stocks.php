<?php

namespace WTG\GraphQL\Queries;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use WTG\Managers\ProductManager;
use WTG\Managers\StockManager;
use WTG\RestClient\Model\Rest\GetProductStocks\Response\Stock;

class Stocks
{
    /**
     * @var StockManager
     */
    protected StockManager $stockManager;
    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * Stocks constructor.
     *
     * @param StockManager $stockManager
     * @param ProductManager $productManager
     */
    public function __construct(StockManager $stockManager, ProductManager $productManager)
    {
        $this->stockManager = $stockManager;
        $this->productManager = $productManager;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return Collection|Stock[]
     * @throws BindingResolutionException
     */
    public function __invoke($_, array $args)
    {
        $products = $this->productManager->findAll($args['skus']);

        return $this->stockManager->fetchStocks($products);
    }
}
