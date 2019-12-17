<?php

declare(strict_types=1);

namespace WTG\Catalog\Api\Model;

use WTG\Models\Product;

/**
 * Price factor model interface.
 *
 * @api
 * @package     WTG\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface PriceFactorInterface
{
    /**
     * Get related product.
     *
     * @return Product
     */
    public function getProduct(): Product;

    /**
     * Set the related product.
     *
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): self;

    /**
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * @return null|string
     */
    public function getErpId(): ?string;

    /**
     * @param string $erpId
     * @return $this
     */
    public function setErpId(string $erpId): self;

    /**
     * @return float
     */
    public function getPricePer(): float;

    /**
     * @param float $pricePer
     * @return $this
     */
    public function setPricePer(float $pricePer): self;

    /**
     * @return float
     */
    public function getPriceFactor(): float;

    /**
     * @param float $priceFactor
     * @return $this
     */
    public function setPriceFactor(float $priceFactor): self;

    /**
     * @return string
     */
    public function getPriceUnit(): string;

    /**
     * @param string $priceUnit
     * @return $this
     */
    public function setPriceUnit(string $priceUnit): self;

    /**
     * @return string
     */
    public function getScaleUnit(): string;

    /**
     * @param string $scaleUnit
     * @return $this
     */
    public function setScaleUnit(string $scaleUnit): self;
}
