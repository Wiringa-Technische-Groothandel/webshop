<?php

declare(strict_types=1);

namespace WTG\Managers;

use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image as ImageFacade;
use Intervention\Image\Image;
use Intervention\Image\ImageCache;
use WTG\Models\Product;

/**
 * Image manager.
 *
 * @package     WTG\Managers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImageManager
{
    public const IMAGE_PLACEHOLDER_PATH = 'storage/static/images/product-image-placeholder.png';
    public const IMAGE_SIZE_LARGE = 'large';
    public const IMAGE_SIZE_MEDIUM = 'medium';
    public const IMAGE_SIZE_SMALL = 'small';
    public const IMAGE_SIZE_ORIGINAL = 'original';

    /**
     * Get the product image.
     *
     * @param Product $product
     * @param string $size
     * @return Image
     */
    public function getProductImage(Product $product, string $size = self::IMAGE_SIZE_LARGE): Image
    {
        $sku = $product->getSku();
        $relativePath = sprintf("storage/uploads/images/products/%s.jpg", $sku);
        $path = public_path($relativePath);

        if (! file_exists($path)) {
            $path = public_path(static::IMAGE_PLACEHOLDER_PATH);
        }

        switch ($size) {
            case self::IMAGE_SIZE_SMALL:
                $width = 100;
                $height = 100;
                break;
            case self::IMAGE_SIZE_MEDIUM:
                $width = 200;
                $height = 200;
                break;
            case self::IMAGE_SIZE_LARGE:
                $width = 300;
                $height = 300;
                break;
            case self::IMAGE_SIZE_ORIGINAL:
                return ImageFacade::make($path);
            default:
                $size = self::IMAGE_SIZE_LARGE;
                $width = 300;
                $height = 300;
        }

        return ImageFacade::cache(
            function (ImageCache $image) use ($height, $width, $path) {
                $image
                    ->make($path)
                    ->trim('top-left', null, 10, 5)
                    ->resize(
                        $width,
                        $height,
                        function (Constraint $constraint) {
                            $constraint->upsize();
                            $constraint->aspectRatio();
                        }
                    )
                    ->resizeCanvas($width, $height);
            },
            60,
            true
        );
    }
}
