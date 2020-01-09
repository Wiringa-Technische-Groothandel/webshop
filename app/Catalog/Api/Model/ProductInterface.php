<?php

declare(strict_types=1);

namespace WTG\Catalog\Api\Model;

use Illuminate\Support\Collection;
use WTG\Catalog\Model\Pack;
use WTG\Catalog\Model\PriceFactor;
use WTG\Contracts\Models\DescriptionContract;

/**
 * Product model interface.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface ProductInterface
{
    public const FIELD_PRICE_FACTOR = 'priceFactor';
    public const FIELD_PACK = 'pack';
    public const FIELD_TYPE = 'type';
    public const FIELD_SERIES = 'series';
    public const FIELD_SKU = 'sku';
    public const FIELD_STOCK_DISPLAY = 'stock_display';
    public const FIELD_RELATED = 'related';
    public const FIELD_KEYWORDS = 'keywords';
    public const FIELD_BRAND = 'brand';
    public const FIELD_INACTIVE = 'inactive';
    public const FIELD_BLOCKED = 'blocked';
    public const FIELD_DISCONTINUED = 'discontinued';
    public const FIELD_VAT = 'vat';
    public const FIELD_WEIGHT = 'weight';
    public const FIELD_WIDTH = 'width';
    public const FIELD_HEIGHT = 'height';
    public const FIELD_LENGTH = 'length';
    public const FIELD_PACKING_UNIT = 'packing_unit';
    public const FIELD_SALES_UNIT = 'sales_unit';
    public const FIELD_EAN = 'ean';
    public const FIELD_NAME = 'name';
    public const FIELD_GROUP = 'group';
    public const FIELD_SUPPLIER_CODE = 'supplier_code';
    public const FIELD_DESCRIPTION = 'description';
    // Relation getters

    /**
     * Get the product description.
     *
     * @return null|DescriptionContract
     */
    public function getDescription(): ?DescriptionContract;

    /**
     * Get the price factor related model.
     *
     * @return PriceFactor
     */
    public function getPriceFactor(): PriceFactor;

    /**
     * Is this product a pack product.
     *
     * @return bool
     */
    public function isPack(): bool;

    /**
     * Get a pack instance.
     *
     * @return null|Pack
     */
    public function getPack(): ?Pack;

    /**
     * Get a pack product instance.
     *
     * @return Collection
     */
    public function getPackProducts(): Collection;

    /**
     * Is this product part of a pack.
     *
     * @return bool
     */
    public function isPackProduct(): bool;

    // Field getters and setters

    /**
     * Get the identifier.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Get the sku.
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set the sku.
     *
     * @param string $sku
     * @return ProductInterface
     */
    public function setSku(string $sku): ProductInterface;

    /**
     * Get the supplier code.
     *
     * @return string
     */
    public function getSupplierCode(): string;

    /**
     * Set the supplier code.
     *
     * @param string $supplierCode
     * @return ProductInterface
     */
    public function setSupplierCode(string $supplierCode): ProductInterface;

    /**
     * Get the product group.
     *
     * @return string
     */
    public function getGroup(): string;

    /**
     * Set the product group.
     *
     * @param string $group
     * @return ProductInterface
     */
    public function setGroup(string $group): ProductInterface;

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the product name.
     *
     * @param string $name
     * @return ProductInterface
     */
    public function setName(string $name): ProductInterface;

    /**
     * Get the product ean.
     *
     * @return string
     */
    public function getEan(): string;

    /**
     * Set the product ean.
     *
     * @param string $ean
     * @return ProductInterface
     */
    public function setEan(string $ean): ProductInterface;

    /**
     * Get the product sales unit.
     *
     * @return ProductInterface
     */
    public function getSalesUnit(): string;

    /**
     * Set the product sales unit.
     *
     * @param string $salesUnit
     * @return ProductInterface
     */
    public function setSalesUnit(string $salesUnit): ProductInterface;

    /**
     * Get the product packing unit.
     *
     * @return ProductInterface
     */
    public function getPackingUnit(): string;

    /**
     * Set the product packing unit.
     *
     * @param string $packingUnit
     * @return ProductInterface
     */
    public function setPackingUnit(string $packingUnit): ProductInterface;

    /**
     * Get the product length.
     *
     * @return float
     */
    public function getLength(): float;

    /**
     * Set the product length.
     *
     * @param float $length
     * @return ProductInterface
     */
    public function setLength(float $length): ProductInterface;

    /**
     * Get the product height.
     *
     * @return float
     */
    public function getHeight(): float;

    /**
     * Set the product height.
     *
     * @param float $height
     * @return ProductInterface
     */
    public function setHeight(float $height): ProductInterface;

    /**
     * Get the product width.
     *
     * @return float
     */
    public function getWidth(): float;

    /**
     * Set the product width.
     *
     * @param float $width
     * @return ProductInterface
     */
    public function setWidth(float $width): ProductInterface;

    /**
     * Get the product weight.
     *
     * @return float
     */
    public function getWeight(): float;

    /**
     * Set the product weight.
     *
     * @param float $weight
     * @return ProductInterface
     */
    public function setWeight(float $weight): ProductInterface;

    /**
     * Get the product vat.
     *
     * @return string
     */
    public function getVat(): string;

    /**
     * Set the product vat.
     *
     * @param string $vat
     * @return ProductInterface
     */
    public function setVat(string $vat): ProductInterface;

    /**
     * Get the product discontinued.
     *
     * @return bool
     */
    public function isDiscontinued(): bool;

    /**
     * Set the product discontinued.
     *
     * @param bool $discontinued
     * @return ProductInterface
     */
    public function setDiscontinued(bool $discontinued): ProductInterface;

    /**
     * Get the product blocked.
     *
     * @return bool
     */
    public function isBlocked(): bool;

    /**
     * Set the product blocked.
     *
     * @param bool $blocked
     * @return ProductInterface
     */
    public function setBlocked(bool $blocked): ProductInterface;

    /**
     * Get the product inactive.
     *
     * @return bool
     */
    public function isInactive(): bool;

    /**
     * Set the product inactive.
     *
     * @param bool $inactive
     * @return ProductInterface
     */
    public function setInactive(bool $inactive): ProductInterface;

    /**
     * Get the product brand.
     *
     * @return string
     */
    public function getBrand(): string;

    /**
     * Set the product brand.
     *
     * @param string $brand
     * @return ProductInterface
     */
    public function setBrand(string $brand): ProductInterface;

    /**
     * Get the product series.
     *
     * @return string
     */
    public function getSeries(): string;

    /**
     * Set the product series.
     *
     * @param string $series
     * @return ProductInterface
     */
    public function setSeries(string $series): ProductInterface;

    /**
     * Get the product type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set the product type.
     *
     * @param string $type
     * @return ProductInterface
     */
    public function setType(string $type): ProductInterface;

    /**
     * Get the product keywords.
     *
     * @return string
     */
    public function getKeywords(): string;

    /**
     * Set the product keywords.
     *
     * @param string $keywords
     * @return ProductInterface
     */
    public function setKeywords(string $keywords): ProductInterface;

    /**
     * Get the product related.
     *
     * @return string
     */
    public function getRelated(): string;

    /**
     * Set the product related.
     *
     * @param string $related
     * @return ProductInterface
     */
    public function setRelated(string $related): ProductInterface;

    /**
     * Get the stock display.
     *
     * @return string
     */
    public function getStockDisplay(): string;

    /**
     * Set the stock display.
     *
     * @param string $stockDisplay
     * @return ProductInterface
     */
    public function setStockDisplay(string $stockDisplay): ProductInterface;
}
