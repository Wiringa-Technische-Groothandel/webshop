<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use Illuminate\Support\Collection;
use WTG\Catalog\Api\Model\ProductInterface;

/**
 * Pack contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface PackContract
{
    /**
     * Get the identifier.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Set the product.
     *
     * @param ProductInterface $product
     * @return PackContract
     */
    public function setProduct(ProductInterface $product): PackContract;

    /**
     * Get the product.
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface;

    /**
     * Get the items.
     *
     * @return Collection
     */
    public function getItems(): Collection;
}
