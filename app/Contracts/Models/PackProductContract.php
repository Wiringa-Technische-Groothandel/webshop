<?php

namespace WTG\Contracts\Models;

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
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Set the pack.
     *
     * @param  PackContract  $pack
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
     * @param  ProductContract  $product
     * @return PackProductContract
     */
    public function setProduct(ProductContract $product): PackProductContract;

    /**
     * Get the product.
     *
     * @return ProductContract
     */
    public function getProduct(): ProductContract;

    /**
     * Set the amount.
     *
     * @param  int  $amount
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