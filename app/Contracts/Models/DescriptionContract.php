<?php

namespace WTG\Contracts\Models;

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
     * @return ProductContract
     */
    public function getProduct(): ProductContract;

    /**
     * Set the related product.
     *
     * @param  ProductContract  $product
     * @return DescriptionContract
     */
    public function setProduct(ProductContract $product): DescriptionContract;

    /**
     * Get the description identifier.
     *
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Set the description value.
     *
     * @param  string  $value
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