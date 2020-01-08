<?php

declare(strict_types=1);

namespace WTG\Catalog\Api\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Product description model interface.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface ProductDescriptionInterface
{
    public const FIELD_VALUE = 'value';

    /**
     * Get the identifier.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Get the related product.
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface;

    /**
     * Set the related product.
     *
     * @param ProductInterface $product
     * @return Model
     */
    public function setProduct(ProductInterface $product): Model;

    /**
     * Set the description value.
     *
     * @param string $value
     * @return ProductDescriptionInterface
     */
    public function setValue(string $value): self;

    /**
     * Get the description value.
     *
     * @return null|string
     */
    public function getValue(): ?string;
}
