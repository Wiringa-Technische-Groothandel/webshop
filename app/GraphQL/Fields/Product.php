<?php

declare(strict_types=1);

namespace WTG\GraphQL\Fields;

use GraphQL\Deferred;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use WTG\GraphQL\Buffers\PriceBuffer;
use WTG\GraphQL\Buffers\StockBuffer;
use WTG\Managers\ImageManager;
use WTG\Managers\PriceManager;
use WTG\Managers\ProductManager;
use WTG\Managers\StockManager;
use WTG\Models\Product as ProductModel;
use WTG\Services\FavoritesService;

/**
 * Product field resolver.
 *
 * @package     WTG\GraphQL\Fields
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Product
{
    protected StockManager $stockManager;
    protected PriceManager $priceManager;
    protected ProductManager $productManager;
    protected ImageManager $imageManager;
    protected FavoritesService $favoritesService;

    /**
     * Product constructor.
     *
     * @param StockManager $stockManager
     * @param PriceManager $priceManager
     * @param ProductManager $productManager
     * @param ImageManager $imageManager
     * @param FavoritesService $favoritesService
     */
    public function __construct(
        StockManager $stockManager,
        PriceManager $priceManager,
        ProductManager $productManager,
        ImageManager $imageManager,
        FavoritesService $favoritesService
    ) {
        $this->stockManager = $stockManager;
        $this->priceManager = $priceManager;
        $this->productManager = $productManager;
        $this->imageManager = $imageManager;
        $this->favoritesService = $favoritesService;
    }

    /**
     * Resolve the 'stock' field for graphql.
     *
     * @param ProductModel $product
     * @return Deferred
     */
    public function resolveStock(ProductModel $product): Deferred
    {
        StockBuffer::add($product);

        return new Deferred(
            function () use ($product) {
                StockBuffer::loadBuffered();

                return StockBuffer::get($product->getSku());
            }
        );
    }

    /**
     * Resolve the 'price' field for graphql.
     *
     * @param ProductModel $product
     * @param array $args
     * @param GraphQLContext $context
     * @return null|Deferred
     */
    public function resolvePrice(
        ProductModel $product,
        array $args,
        GraphQLContext $context
    ): ?Deferred {
        if (! $context->user()) {
            return null;
        }

        PriceBuffer::add($product);

        return new Deferred(
            function () use ($product) {
                PriceBuffer::loadBuffered();

                $price = PriceBuffer::get($product->getSku());

                if (! $price) {
                    return null;
                }

                $netPrice = ($price->netPrice * $price->priceFactor) / $price->pricePer;
                $grossPrice = ($price->grossPrice * $price->priceFactor) / $price->pricePer;

                return [
                    'isAction'    => $price->isAction,
                    'net'         => $netPrice,
                    'gross'       => $grossPrice,
                    'scaleUnit'   => $price->scaleUnit,
                    'priceUnit'   => $price->priceUnit,
                    'pricePer'    => $price->pricePer,
                    'priceFactor' => $price->priceFactor,
                ];
            }
        );
    }

    /**
     * Resolve the 'related_products' field for graphql.
     *
     * @param ProductModel $product
     * @return Collection
     */
    public function resolveRelatedProducts(ProductModel $product): Collection
    {
        return $this->productManager->getRelatedProducts($product);
    }

    /**
     * Resolve the 'image' field for graphql.
     *
     * @param ProductModel $product
     * @param array $args
     * @return string
     */
    public function resolveImage(
        ProductModel $product,
        array $args
    ): string {
        $image = $this->imageManager->getProductImage($product, $args['size']);

        return (string)$image->encode('data-url');
    }

    /**
     * Resolve the 'description' field for graphql.
     *
     * @param ProductModel $product
     * @return string|null
     */
    public function resolveDescription(ProductModel $product): ?string
    {
        $description = $product->getDescription();

        return $description ? $description->getValue() : null;
    }

    /**
     * Resolve the 'sales_unit' field for graphql.
     *
     * @param ProductModel $product
     * @return string[]
     */
    public function resolveSalesUnit(ProductModel $product): array
    {
        $salesUnitSingular = unit_to_str($product->getSalesUnit(), false);
        $salesUnitPlural = unit_to_str($product->getSalesUnit(), true);

        return [
            'singular' => ucfirst($salesUnitSingular),
            'plural'   => ucfirst($salesUnitPlural),
        ];
    }

    /**
     * Resolve the 'isFavorite' field for graphql.
     *
     * @param ProductModel $product
     * @param array $args
     * @param GraphQLContext $context
     * @return bool|null
     */
    public function resolveIsFavorite(ProductModel $product, array $args, GraphQLContext $context): ?bool
    {
        $user = $context->user();

        if (! $user) {
            return null;
        }

        return $this->favoritesService->isFavorite($product, $user);
    }
}
