<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use WTG\Catalog\Api\Model\ProductInterface;

/**
 * Description contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface DescriptionContract
{
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
     * @return DescriptionContract
     */
    public function setProduct(ProductInterface $product): DescriptionContract;

    /**
     * Get the description identifier.
     *
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Set the description value.
     *
     * @param string $value
     * @return DescriptionContract
     */
    public function setValue(string $value): DescriptionContract;

    /**
     * Get the description value.
     *
     * @return null|string
     */
    public function getValue(): ?string;
}
