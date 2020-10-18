<?php

declare(strict_types=1);

namespace WTG\GraphQL\Fields;

use Intervention\Image\Facades\Image;
use WTG\Catalog\Api\ProductInterface;
use WTG\Managers\ProductManager;
use WTG\Models\Product as ProductModel;
use WTG\Models\Slide as SlideModel;

/**
 * Slide field resolvers.
 *
 * @package     WTG\GraphQL\Fields
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Slide
{
    private ProductManager $productManager;

    /**
     * Slide constructor.
     *
     * @param ProductManager $productManager
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * Resolve the 'image' field for graphql.
     *
     * @param SlideModel $slide
     * @param array $args
     * @return string
     */
    public function resolveImage(
        SlideModel $slide,
        array $args
    ): string {
        $image = Image::make(sprintf(storage_path('app/public/uploads/images/carousel/%s'), $slide->getImage()))
            ->resize(
                null,
                300,
                function ($constraint) {
                    $constraint->aspectRatio();
                }
            );

        return (string) $image->encode('data-url');
    }

    /**
     * Resolve the 'product' field for graphql.
     *
     * @param SlideModel $slide
     * @param array $args
     * @return ProductModel|null
     */
    public function resolveProduct(
        SlideModel $slide,
        array $args
    ): ?ProductModel {
        $link = $slide->getLink();
        $linkType = $slide->getLinkType();

        if (! $link || $linkType !== SlideModel::LINK_TYPE_PRODUCT) {
            return null;
        }

        return $this->productManager->find($link);
    }
}
