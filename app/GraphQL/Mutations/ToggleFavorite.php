<?php

namespace WTG\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use WTG\Managers\ProductManager;
use WTG\Services\FavoritesService;

class ToggleFavorite
{
    protected FavoritesService $favoritesService;
    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * ToggleFavorite constructor.
     *
     * @param FavoritesService $favoritesService
     * @param ProductManager $productManager
     */
    public function __construct(FavoritesService $favoritesService, ProductManager $productManager)
    {
        $this->favoritesService = $favoritesService;
        $this->productManager = $productManager;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @param GraphQLContext $context
     * @return bool
     */
    public function __invoke($_, array $args, GraphQLContext $context)
    {
        $product = $this->productManager->find($args['sku']);

        return $this->favoritesService->toggleFavorite($product, $context->user());
    }
}
