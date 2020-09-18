<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use WTG\Catalog\Api\ProductInterface;
use WTG\Contracts\Models\DescriptionContract;
use WTG\Foundation\Api\ErpModelInterface;
use WTG\Foundation\Api\SoftDeletableInterface;
use WTG\Foundation\Traits\HasErpId;
use WTG\Foundation\Traits\HasId;
use WTG\Foundation\Traits\HasSynchronizedAt;
use WTG\Foundation\Traits\SoftDeletes;
use WTG\Managers\ProductManager;

/**
 * Product model.
 *
 * @package     WTG\Catalog\Model
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Product extends Model implements ProductInterface, ErpModelInterface, SoftDeletableInterface
{
    use HasId;
    use HasErpId;
    use HasSynchronizedAt;
    use Searchable;
    use SoftDeletes;

    public const DEFAULT_STOCK_DISPLAY = 'S';

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
        'is_web',
        'description',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'description'
    ];

    /**
     * Description relation.
     *
     * @return HasOne
     */
    public function description(): HasOne
    {
        return $this->hasOne(Description::class);
    }

    // Model relations

    /**
     * Get the product description.
     *
     * @return null|DescriptionContract
     */
    public function getDescription(): ?DescriptionContract
    {
        return $this->getAttribute(self::FIELD_DESCRIPTION);
    }

    /**
     * Is this product a pack product.
     *
     * @return bool
     */
    public function isPack(): bool
    {
        return $this->pack()->exists();
    }

    /**
     * Pack relation.
     *
     * @return HasOne
     */
    public function pack(): HasOne
    {
        return $this->hasOne(Pack::class);
    }

    /**
     * Get a pack instance.
     *
     * @return null|Pack
     */
    public function getPack(): ?Pack
    {
        return $this->getAttribute(self::FIELD_PACK);
    }

    /**
     * Is this product part of a pack.
     *
     * @return bool
     */
    public function isPackProduct(): bool
    {
        return $this->getPackProducts()->isNotEmpty();
    }

    /**
     * Get a pack product instance.
     *
     * @return Collection
     */
    public function getPackProducts(): Collection
    {
        return $this->packProducts()->get();
    }

    /**
     * Pack products relation.
     *
     * @return HasMany
     */
    public function packProducts(): HasMany
    {
        return $this->hasMany(PackProduct::class);
    }

    /**
     * Set the sku.
     *
     * @param string $sku
     * @return ProductInterface
     */
    public function setSku(string $sku): ProductInterface
    {
        return $this->setAttribute(self::FIELD_SKU, $sku);
    }

    /**
     * Get the supplier code.
     *
     * @return string
     */
    public function getSupplierCode(): string
    {
        return $this->getAttribute(self::FIELD_SUPPLIER_CODE);
    }

    // Model field getters and setters

    /**
     * Set the supplier code.
     *
     * @param string $supplierCode
     * @return ProductInterface
     */
    public function setSupplierCode(string $supplierCode): ProductInterface
    {
        return $this->setAttribute(self::FIELD_SUPPLIER_CODE, $supplierCode);
    }

    /**
     * Get the product group.
     *
     * @return string
     */
    public function getGroup(): string
    {
        return (string)$this->getAttribute(self::FIELD_GROUP);
    }

    /**
     * Set the product group.
     *
     * @param string $group
     * @return ProductInterface
     */
    public function setGroup(string $group): ProductInterface
    {
        return $this->setAttribute(self::FIELD_GROUP, $group);
    }

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getAttribute(self::FIELD_NAME);
    }

    /**
     * Set the product name.
     *
     * @param string $name
     * @return ProductInterface
     */
    public function setName(string $name): ProductInterface
    {
        return $this->setAttribute(self::FIELD_NAME, $name);
    }

    /**
     * Get the product ean.
     *
     * @return string
     */
    public function getEan(): string
    {
        return $this->getAttribute(self::FIELD_EAN);
    }

    /**
     * Set the product ean.
     *
     * @param string $ean
     * @return ProductInterface
     */
    public function setEan(string $ean): ProductInterface
    {
        return $this->setAttribute(self::FIELD_EAN, $ean);
    }

    /**
     * Set the product sales unit.
     *
     * @param string $salesUnit
     * @return ProductInterface
     */
    public function setSalesUnit(string $salesUnit): ProductInterface
    {
        return $this->setAttribute(self::FIELD_SALES_UNIT, $salesUnit);
    }

    /**
     * Get the product packing unit.
     *
     * @return ProductInterface
     */
    public function getPackingUnit(): string
    {
        return $this->getAttribute(self::FIELD_PACKING_UNIT);
    }

    /**
     * Set the product packing unit.
     *
     * @param string $packingUnit
     * @return ProductInterface
     */
    public function setPackingUnit(string $packingUnit): ProductInterface
    {
        return $this->setAttribute(self::FIELD_PACKING_UNIT, $packingUnit);
    }

    /**
     * Get the product length.
     *
     * @return float
     */
    public function getLength(): float
    {
        return (float)$this->getAttribute(self::FIELD_LENGTH);
    }

    /**
     * Set the product length.
     *
     * @param float $length
     * @return ProductInterface
     */
    public function setLength(float $length): ProductInterface
    {
        return $this->setAttribute(self::FIELD_LENGTH, $length);
    }

    /**
     * Get the product height.
     *
     * @return float
     */
    public function getHeight(): float
    {
        return (float)$this->getAttribute(self::FIELD_HEIGHT);
    }

    /**
     * Set the product height.
     *
     * @param float $height
     * @return ProductInterface
     */
    public function setHeight(float $height): ProductInterface
    {
        return $this->setAttribute(self::FIELD_HEIGHT, $height);
    }

    /**
     * Get the product width.
     *
     * @return float
     */
    public function getWidth(): float
    {
        return (float)$this->getAttribute(self::FIELD_WIDTH);
    }

    /**
     * Set the product width.
     *
     * @param float $width
     * @return ProductInterface
     */
    public function setWidth(float $width): ProductInterface
    {
        return $this->setAttribute(self::FIELD_WIDTH, $width);
    }

    /**
     * Get the product weight.
     *
     * @return float
     */
    public function getWeight(): float
    {
        return (float)$this->getAttribute(self::FIELD_WEIGHT);
    }

    /**
     * Set the product weight.
     *
     * @param float $weight
     * @return ProductInterface
     */
    public function setWeight(float $weight): ProductInterface
    {
        return $this->setAttribute(self::FIELD_WEIGHT, $weight);
    }

    /**
     * Get the product vat.
     *
     * @return string
     */
    public function getVat(): string
    {
        return $this->getAttribute(self::FIELD_VAT);
    }

    /**
     * Set the product vat.
     *
     * @param string $vat
     * @return ProductInterface
     */
    public function setVat(string $vat): ProductInterface
    {
        return $this->setAttribute(self::FIELD_VAT, $vat);
    }

    /**
     * Get the product discontinued.
     *
     * @return bool
     */
    public function isDiscontinued(): bool
    {
        return (bool)$this->getAttribute(self::FIELD_DISCONTINUED);
    }

    /**
     * Set the product discontinued.
     *
     * @param bool $discontinued
     * @return ProductInterface
     */
    public function setDiscontinued(bool $discontinued): ProductInterface
    {
        return $this->setAttribute(self::FIELD_DISCONTINUED, $discontinued);
    }

    /**
     * Get the product blocked.
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        return (bool)$this->getAttribute(self::FIELD_BLOCKED);
    }

    /**
     * Set the product blocked.
     *
     * @param bool $blocked
     * @return ProductInterface
     */
    public function setBlocked(bool $blocked): ProductInterface
    {
        return $this->setAttribute(self::FIELD_BLOCKED, $blocked);
    }

    /**
     * Get the product inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return (bool)$this->getAttribute(self::FIELD_INACTIVE);
    }

    /**
     * Set the product inactive.
     *
     * @param bool $inactive
     * @return ProductInterface
     */
    public function setInactive(bool $inactive): ProductInterface
    {
        return $this->setAttribute(self::FIELD_INACTIVE, $inactive);
    }

    /**
     * Get the product brand.
     *
     * @return string
     */
    public function getBrand(): string
    {
        return $this->getAttribute(self::FIELD_BRAND);
    }

    /**
     * Set the product brand.
     *
     * @param string $brand
     * @return ProductInterface
     */
    public function setBrand(string $brand): ProductInterface
    {
        return $this->setAttribute(self::FIELD_BRAND, $brand);
    }

    /**
     * Get the product series.
     *
     * @return string
     */
    public function getSeries(): string
    {
        return $this->getAttribute(self::FIELD_SERIES);
    }

    /**
     * Set the product series.
     *
     * @param string $series
     * @return ProductInterface
     */
    public function setSeries(string $series): ProductInterface
    {
        return $this->setAttribute(self::FIELD_SERIES, $series);
    }

    /**
     * Get the product type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getAttribute(self::FIELD_TYPE);
    }

    /**
     * Set the product type.
     *
     * @param string $type
     * @return ProductInterface
     */
    public function setType(string $type): ProductInterface
    {
        return $this->setAttribute(self::FIELD_TYPE, $type);
    }

    /**
     * Get the product keywords.
     *
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->getAttribute(self::FIELD_KEYWORDS);
    }

    /**
     * Set the product keywords.
     *
     * @param string $keywords
     * @return ProductInterface
     */
    public function setKeywords(string $keywords): ProductInterface
    {
        return $this->setAttribute(self::FIELD_KEYWORDS, $keywords);
    }

    /**
     * Get the product related.
     *
     * @return string
     */
    public function getRelated(): string
    {
        return $this->getAttribute(self::FIELD_RELATED);
    }

    /**
     * Set the product related.
     *
     * @param string $related
     * @return ProductInterface
     */
    public function setRelated(string $related): ProductInterface
    {
        return $this->setAttribute(self::FIELD_RELATED, $related);
    }

    /**
     * Set the stock display.
     *
     * @param string $stockDisplay
     * @return ProductInterface
     */
    public function setStockDisplay(string $stockDisplay): ProductInterface
    {
        return $this->setAttribute(self::FIELD_TYPE, $stockDisplay);
    }

    /**
     * Get the searchable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $searchableArray = [];

        foreach ($this->getSearchableFields() as $name => $field) {
            $values = [];
            $name = is_numeric($name) ? $field : $name;

            if (is_array($field)) {
                foreach ($field as $f) {
                    $values[] = $this->getAttribute($f);
                }
            } elseif (str_contains($field, '.')) {
                $values = Arr::get($this->toArray(), $field);

                if (! is_array($values)) {
                    $values = [$values];
                }
            } else {
                $values = [$this->getAttributeValue($field)];
            }

            $searchableArray[$name] = join(' ', $values);
        }

        return $searchableArray;
    }

    /**
     * Get the series / type path.
     *
     * @return string
     * @deprecated use \WTG\Catalog\ProductManager::getProductCategoryPath
     */
    public function getPath(): string
    {
        return sprintf(
            '%s  /  %s',
            $this->getAttribute(self::FIELD_SERIES),
            $this->getAttribute(self::FIELD_TYPE)
        );
    }

    /**
     * Get the url for the product.
     *
     * @return string
     * @deprecated use \WTG\Catalog\ProductManager::getProductUrl
     */
    public function getUrl(): string
    {
        /** @var ProductManager $productManager */
        $productManager = app(ProductManager::class);

        return $productManager->getProductUrl($this);
    }

    /**
     * Get the stock display.
     *
     * @return string
     */
    public function getStockDisplay(): string
    {
        return $this->getAttribute(self::FIELD_STOCK_DISPLAY) ?: self::DEFAULT_STOCK_DISPLAY;
    }

    /**
     * Get the sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return (string)$this->getAttribute(self::FIELD_SKU);
    }

    /**
     * Get the product sales unit.
     *
     * @return ProductInterface
     */
    public function getSalesUnit(): string
    {
        return $this->getAttribute(self::FIELD_SALES_UNIT) ?: '';
    }

    /**
     * Get the minimal purchase amount.
     *
     * @return float
     */
    public function getMinimalPurchaseAmount(): float
    {
        $minimalPurchase = $this->getAttributeFromArray('minimal_purchase');

        return $minimalPurchase > 0.0 ? $minimalPurchase : 1.0;
    }

    /**
     * Get the list of searchable fields.
     *
     * @return array
     */
    protected function getSearchableFields(): array
    {
        return [
            'sku',
            'group',
            'description'      => ['name', 'keywords'],
            'brand',
            'series',
            'type',
            'ean',
            'supplier_code',
            'long_description' => 'description.value'
        ];
    }
}
