<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

/**
 * Product contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface ProductContract
{
    /**
     * Does the product have a description.
     *
     * @return bool
     */
    public function hasDescription(): bool;

    /**
     * Does the product have a description.
     *
     * @return null|DescriptionContract
     */
    public function getDescription(): ?DescriptionContract;

    /**
     * Get the product sku.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set the product sku.
     *
     * @param string $sku
     * @return ProductContract
     */
    public function setSku(string $sku): ProductContract;

    /**
     * Get the product sku.
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set the product group.
     *
     * @param string $group
     * @return ProductContract
     */
    public function setGroup(string $group): ProductContract;

    /**
     * Get the product group.
     *
     * @return string
     */
    public function getGroup(): string;

    /**
     * Get or set the product name.
     *
     * @param string $name
     * @return ProductContract
     */
    public function setName(string $name): ProductContract;

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the product ean.
     *
     * @param string $ean
     * @return ProductContract
     */
    public function setEan(string $ean): ProductContract;

    /**
     * Get the product ean.
     *
     * @return string
     */
    public function getEan(): string;

    /**
     * Set the product brand.
     *
     * @param string $brand
     * @return ProductContract
     */
    public function setBrand(string $brand): ProductContract;

    /**
     * Get the product brand.
     *
     * @return string
     */
    public function getBrand(): string;

    /**
     * Set the product series.
     *
     * @param string $series
     * @return ProductContract
     */
    public function setSeries(string $series): ProductContract;

    /**
     * Get the product series.
     *
     * @return string
     */
    public function getSeries(): string;

    /**
     * Set the product type.
     *
     * @param string $type
     * @return ProductContract
     */
    public function setType(string $type): ProductContract;

    /**
     * Get the product type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set the product sales unit.
     *
     * @param string $salesUnit
     * @return ProductContract
     */
    public function setSalesUnit(string $salesUnit): ProductContract;

    /**
     * Get the product sales unit.
     *
     * @return string
     */
    public function getSalesUnit(): string;

    /**
     * Set the stock display.
     *
     * @param string $stockDisplay
     * @return ProductContract
     */
    public function setStockDisplay(string $stockDisplay): ProductContract;

    /**
     * Get the stock display.
     *
     * @return string|null
     */
    public function getStockDisplay(): string;
}
