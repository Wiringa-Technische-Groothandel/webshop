<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\Model\Product;
use WTG\Contracts\Models\DescriptionContract;

/**
 * Description model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Description extends Model implements DescriptionContract
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the related product.
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        return $this->getAttribute('product');
    }

    /**
     * Set the related product.
     *
     * @param ProductInterface $product
     * @return DescriptionContract
     */
    public function setProduct(ProductInterface $product): DescriptionContract
    {
        $this->product()->associate($product);

        return $this;
    }

    /**
     * Product relation.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the description identifier.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the description value.
     *
     * @param string $value
     * @return DescriptionContract
     */
    public function setValue(string $value): DescriptionContract
    {
        return $this->setAttribute('value', $value);
    }

    /**
     * Get the description value.
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        $string = $this->getAttribute('value');

        return $string ? (string)(new HtmlString($string)) : null;
    }
}
