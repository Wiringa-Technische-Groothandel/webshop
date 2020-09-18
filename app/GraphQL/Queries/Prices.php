<?php

namespace WTG\GraphQL\Queries;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use WTG\Managers\PriceManager;
use WTG\Managers\ProductManager;
use WTG\Models\Customer;
use WTG\RestClient\Model\Rest\GetProductPrices\Response\Price;

class Prices
{
    protected PriceManager $priceManager;
    protected ProductManager $productManager;

    /**
     * Prices constructor.
     *
     * @param PriceManager $priceManager
     * @param ProductManager $productManager
     */
    public function __construct(PriceManager $priceManager, ProductManager $productManager)
    {
        $this->priceManager = $priceManager;
        $this->productManager = $productManager;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @param GraphQLContext $context
     * @return Collection|Price[]
     * @throws BindingResolutionException
     */
    public function __invoke($_, array $args, GraphQLContext $context)
    {
        /** @var Customer $customer */
        $customer = $context->user();

        if (! $customer) {
            return collect();
        }

        $products = $this->productManager->findAll($args['skus']);

        return $this->priceManager->fetchPrices(
            $customer->getCompany()->getCustomerNumber(),
            $products
        );
    }
}
