<?php

declare(strict_types=1);

namespace WTG\Catalog\Model;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Laravel\Scout\Searchable;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\ProductManager;
use WTG\Catalog\StockManager;
use WTG\Contracts\Models\DescriptionContract;
use WTG\Foundation\Api\ErpModelInterface;
use WTG\Foundation\Api\SoftDeletableInterface;
use WTG\Foundation\Traits\HasErpId;
use WTG\Foundation\Traits\HasId;
use WTG\Foundation\Traits\HasSynchronizedAt;
use WTG\Foundation\Traits\SoftDeletes;
use WTG\Models\Description;

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

    public const IMAGE_PLACEHOLDER_PATH = 'storage/static/images/product-image-placeholder.png';
    public const IMAGE_PLACEHOLDER_CACHE_KEY = 'product-image-placeholder';
    public const IMAGE_SIZE_LARGE = 'large';
    public const IMAGE_SIZE_MEDIUM = 'medium';
    public const IMAGE_SIZE_SMALL = 'small';
    public const IMAGE_SIZE_ORIGINAL = 'original';
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
    protected $appends = [
        'price_per_str',
        'stock',
        'stock_status',
        'path',
        'image',
        'small_image',
        'medium_image',
        'large_image',
        'sales_unit',
        'sales_unit_plural',
    ];

    /**
     * @var bool
     */
    public static $withoutAppends = false;

    /**
     * @return array
     */
    protected function getArrayableAppends()
    {
        if (self::$withoutAppends) {
            return [];
        }
        return parent::getArrayableAppends();
    }

    // Model relations

    /**
     * Description relation.
     *
     * @return HasOne
     */
    public function description(): HasOne
    {
        return $this->hasOne(Description::class);
    }

    /**
     * Get the product description.
     *
     * @return null|DescriptionContract
     */
    public function getDescription(): ?DescriptionContract
    {
        return $this->getAttributeFromArray(self::FIELD_DESCRIPTION);
    }

    /**
     * Price factor relation.
     *
     * @return HasOne
     */
    public function priceFactor(): HasOne
    {
        return $this->hasOne(PriceFactor::class);
    }

    /**
     * Get the price factor related model.
     *
     * @return PriceFactor
     */
    public function getPriceFactor(): PriceFactor
    {
        return $this->getAttributeFromArray(self::FIELD_PRICE_FACTOR) ?: new PriceFactor();
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
        return $this->getAttributeFromArray(self::FIELD_PACK);
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

    // Model field getters and setters

    /**
     * Get the supplier code.
     *
     * @return string
     */
    public function getSupplierCode(): string
    {
        return $this->getAttributeFromArray(self::FIELD_SUPPLIER_CODE);
    }

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
        return (string)$this->getAttributeFromArray(self::FIELD_GROUP);
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
        return $this->getAttributeFromArray(self::FIELD_NAME);
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
        return $this->getAttributeFromArray(self::FIELD_EAN);
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
        return $this->getAttributeFromArray(self::FIELD_PACKING_UNIT);
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
        return (float)$this->getAttributeFromArray(self::FIELD_LENGTH);
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
        return (float)$this->getAttributeFromArray(self::FIELD_HEIGHT);
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
        return (float)$this->getAttributeFromArray(self::FIELD_WIDTH);
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
        return (float)$this->getAttributeFromArray(self::FIELD_WEIGHT);
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
     * @return float
     */
    public function getVat(): string
    {
        return $this->getAttributeFromArray(self::FIELD_VAT);
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
        return (bool)$this->getAttributeFromArray(self::FIELD_DISCONTINUED);
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
        return (bool)$this->getAttributeFromArray(self::FIELD_BLOCKED);
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
        return (bool)$this->getAttributeFromArray(self::FIELD_INACTIVE);
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
        return $this->getAttributeFromArray(self::FIELD_BRAND);
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
        return $this->getAttributeFromArray(self::FIELD_SERIES);
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
        return $this->getAttributeFromArray(self::FIELD_TYPE);
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
        return $this->getAttributeFromArray(self::FIELD_KEYWORDS);
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
        return $this->getAttributeFromArray(self::FIELD_RELATED);
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
                    $values[] = $this->$f;
                }
            } else {
                $values = [$this->$field];
            }

            $searchableArray[$name] = join(' ', $values);
        }

        return $searchableArray;
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
            'description' => ['name', 'keywords'],
            'brand',
            'series',
            'type',
            'ean',
            'supplier_code',
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getSmallImageAttribute(): string
    {
        return $this->getImageUrl(self::IMAGE_SIZE_SMALL);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getMediumImageAttribute(): string
    {
        return $this->getImageUrl(self::IMAGE_SIZE_MEDIUM);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getLargeImageAttribute(): string
    {
        return $this->getImageUrl(self::IMAGE_SIZE_LARGE);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getImageAttribute(): string
    {
        return $this->getImageUrl(self::IMAGE_SIZE_ORIGINAL);
    }

    /**
     * Get the product image url.
     *
     * @param string $size
     * @return string
     * @throws Exception
     */
    public function getImageUrl(string $size = self::IMAGE_SIZE_LARGE): string
    {
        $relativePath = sprintf("storage/uploads/images/products/%s.jpg", $this->getAttributeFromArray(self::FIELD_SKU));

        switch ($size) {
            case self::IMAGE_SIZE_SMALL:
                $width = 100;
                $height = 100;
                break;
            case self::IMAGE_SIZE_MEDIUM:
                $width = 200;
                $height = 200;
                break;
            case self::IMAGE_SIZE_LARGE:
                $width = 300;
                $height = 300;
                break;
            case self::IMAGE_SIZE_ORIGINAL:
                return url($relativePath);
            default:
                $size = self::IMAGE_SIZE_LARGE;
                $width = 300;
                $height = 300;
        }

        $path = public_path($relativePath);
        $cacheKey = 'product-image-' . $this->getAttributeFromArray(self::FIELD_SKU) . '-' . $size;

        if (! file_exists($path)) {
            $path = public_path(static::IMAGE_PLACEHOLDER_PATH);
            $cacheKey = static::IMAGE_PLACEHOLDER_CACHE_KEY . '-' . $size;
        }

        return (string) Cache::tags(['product-images'])->remember(
            $cacheKey,
            60 * 60 * 24,
            function () use ($path, $width, $height) {
                return Image::make($path)
                    ->trim('top-left', null, 10, 5)
                    ->resize(
                        $width,
                        $height,
                        function ($constraint) {
                            $constraint->upsize();
                            $constraint->aspectRatio();
                        }
                    )
                    ->resizeCanvas($width, $height)
                    ->encode('data-url');
            }
        );
    }

    /**
     * @return string
     */
    public function getPathAttribute(): string
    {
        return $this->getPath();
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
            $this->getAttributeFromArray(self::FIELD_SERIES),
            $this->getAttributeFromArray(self::FIELD_TYPE)
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
     * Stock status accessor.
     *
     * @return string
     * @throws Exception
     */
    public function getStockStatusAttribute(): string
    {
        return $this->getStockStatus();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getStockStatus(): string
    {
        switch ($this->getStockDisplay()) {
            case 'S':
                $stock = $this->getStock();
                $status = $stock ?
                    sprintf(
                        '<span class="d-none d-md-inline">Voorraad: </span> %s %s',
                        $stock['availableStock'],
                        unit_to_str($this->getSalesUnit(), $stock['availableStock'] > 1)
                    ) :
                    'In bestelling, bel voor meer info';
                break;
            case 'A':
                $status = 'Levertijd in overleg';
                break;
            case 'V':
                $status = 'Binnen 24/48 uur mits voor 16.00 besteld';
                break;
            default:
                $status = '';
        }

        return $status;
    }

    /**
     * Get the stock display.
     *
     * @return string
     */
    public function getStockDisplay(): string
    {
        return $this->getAttributeFromArray(self::FIELD_STOCK_DISPLAY) ?: self::DEFAULT_STOCK_DISPLAY;
    }

    /**
     * @return null|array
     * @throws Exception
     */
    public function getStock(): ?array
    {
        return cache()->remember(
            'product-stock-' . $this->getSku(),
            60 * 5, // 5 minutes
            function () {
                /** @var StockManager $stockManager */
                $stockManager = app(StockManager::class);
                $stock = $stockManager->fetchStock($this->getSku());

                if ($stock) {
                    return get_object_vars($stock);
                }

                return null;
            }
        );
    }

    /**
     * Get the sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return (string)$this->getAttributeFromArray(self::FIELD_SKU);
    }

    /**
     * @return string
     */
    public function getSalesUnitAttribute(): string
    {
        return unit_to_str($this->getSalesUnit(), false);
    }

    /**
     * @return string
     */
    public function getSalesUnitPluralAttribute(): string
    {
        return unit_to_str($this->getSalesUnit(), true);
    }

    /**
     * Get the product sales unit.
     *
     * @return ProductInterface
     */
    public function getSalesUnit(): string
    {
        return $this->getAttributeFromArray(self::FIELD_SALES_UNIT) ?: '';
    }

    /**
     * Stock accessor.
     *
     * @return null|array
     * @throws Exception
     */
    public function getStockAttribute(): ?array
    {
        return $this->getStock();
    }

    /**
     * Price per string accessor.
     *
     * @return string
     * @throws Exception
     */
    public function getPricePerStrAttribute(): string
    {
        return $this->getPricePerStr();
    }

    /**
     * @return string
     */
    public function getPricePerStr(): string
    {
        if (! $this->getPriceFactor()->getId()) {
            return '';
        }

        if ($this->getPriceFactor()->getPriceUnit() === 'DAG') {
            $pricePerString = sprintf('Huurprijs per dag');
        } elseif ($this->getPriceFactor()->getScaleUnit() === $this->getPriceFactor()->getPriceUnit()) {
            $pricePerString = sprintf(
                'Prijs per %s',
                unit_to_str($this->getPriceFactor()->getPriceUnit(), false)
            );
        } else {
            $pricePerString = sprintf(
                'Prijs per %s van %s %s',
                unit_to_str($this->getPriceFactor()->getScaleUnit(), false),
                $this->getPriceFactor()->getPriceFactor(),
                unit_to_str($this->getPriceFactor()->getPriceUnit(), $this->getPriceFactor()->getPriceFactor() > 1)
            );
        }

        return $pricePerString;
    }
}
