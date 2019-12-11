<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

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
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Set the product.
     *
     * @param  ProductContract  $product
     * @return PackContract
     */
    public function setProduct(ProductContract $product): PackContract;

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