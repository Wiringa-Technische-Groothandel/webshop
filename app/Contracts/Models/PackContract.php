<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use WTG\Catalog\Model\Product;
use Illuminate\Support\Collection;

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
     * @param Product $product
     * @return PackContract
     */
    public function setProduct(Product $product): PackContract;

    /**
     * Get the product.
     *
     * @return ProductContract
     */
    public function getProduct(): ProductContract;

    /**
     * Get the items.
     *
     * @return Collection
     */
    public function getItems(): Collection;
}
