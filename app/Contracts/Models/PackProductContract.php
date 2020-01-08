<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use WTG\Catalog\Model\Product;

/**
 * Pack product contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface PackProductContract
{
    /**
     * Get the identifier.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Set the pack.
     *
     * @param PackContract $pack
     * @return PackProductContract
     */
    public function setPack(PackContract $pack): PackProductContract;

    /**
     * Get the pack.
     *
     * @return PackContract
     */
    public function getPack(): PackContract;

    /**
     * Set the product.
     *
     * @param Product $product
     * @return PackProductContract
     */
    public function setProduct(Product $product): PackProductContract;

    /**
     * Get the product.
     *
     * @return Product
     */
    public function getProduct(): Product;

    /**
     * Set the amount.
     *
     * @param int $amount
     * @return PackProductContract
     */
    public function setAmount(int $amount): PackProductContract;

    /**
     * Get the amount.
     *
     * @return null|int
     */
    public function getAmount(): ?int;
}
