<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Builder;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Luna\SeoUrls\SeoUrl;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\DescriptionContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Product model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Product extends Model implements ProductContract
{
    use Searchable, SoftDeletes;

    const IMAGE_PLACEHOLDER_PATH = 'storage/static/images/product-image-placeholder.png';
    const IMAGE_PLACEHOLDER_CACHE_KEY = 'product-image-placeholder';

    const IMAGE_SIZE_LARGE = 'large';
    const IMAGE_SIZE_MEDIUM = 'medium';
    const IMAGE_SIZE_SMALL = 'small';

    const DEFAULT_STOCK_DISPLAY = 'S';

    /**
     * @var array
     */
    protected $guarded = ['id', 'webshop'];

    /**
     * @var string[]
     */
    protected $appends = ['sales_unit_long'];

    /**
     * @return string
     */
    protected function getSalesUnitLongAttribute(): string
    {
        return unit_to_str($this->getSalesUnit(), false);
    }

    /**
     * Description relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected function description(): HasOne
    {
        return $this->hasOne(Description::class);
    }

    /**
     * SeoUrl relation.
     *
     * @return HasOne
     */
    protected function seoUrl(): HasOne
    {
        return $this->hasOne(SeoUrl::class);
    }

    /**
     * Pack relation.
     *
     * @return HasOne
     */
    protected function pack(): HasOne
    {
        return $this->hasOne(Pack::class);
    }

    /**
     * Pack products relation.
     *
     * @return HasMany
     */
    protected function packProducts(): HasMany
    {
        return $this->hasMany(PackProduct::class);
    }

    /**
     * Does the product have a description.
     *
     * @return bool
     */
    public function hasDescription(): bool
    {
        return (bool) $this->description()->exists();
    }

    /**
     * Does the product have a description.
     *
     * @return null|DescriptionContract
     */
    public function getDescription(): ?DescriptionContract
    {
        return $this->getAttribute('description');
    }

    /**
     * Product identifier.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the sku.
     *
     * @param  string  $sku
     * @return ProductContract
     */
    public function setSku(string $sku): ProductContract
    {
        return $this->setAttribute('sku', $sku);
    }

    /**
     * Get the sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return (string) $this->getAttribute('sku');
    }

    /**
     * Set the product group.
     *
     * @param  string  $group
     * @return ProductContract
     */
    public function setGroup(string $group): ProductContract
    {
        return $this->setAttribute('group', $group);
    }

    /**
     * Get the product group.
     *
     * @return string
     */
    public function getGroup(): string
    {
        return (string) $this->getAttribute('group');
    }

    /**
     * Set the product name.
     *
     * @param  string  $name
     * @return ProductContract
     */
    public function setName(string $name): ProductContract
    {
        return $this->setAttribute('name', $name);
    }

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    /**
     * Set the product ean.
     *
     * @param  string  $ean
     * @return ProductContract
     */
    public function setEan(string $ean): ProductContract
    {
        return $this->setAttribute('ean', $ean);
    }

    /**
     * Get the product ean.
     *
     * @return string
     */
    public function getEan(): string
    {
        return $this->getAttribute('ean');
    }

    /**
     * Set the product brand.
     *
     * @param  string  $brand
     * @return ProductContract
     */
    public function setBrand(string $brand): ProductContract
    {
        return $this->setAttribute('brand', $brand);
    }

    /**
     * Get the product brand.
     *
     * @return string
     */
    public function getBrand(): string
    {
        return $this->getAttribute('brand');
    }

    /**
     * Set the product series.
     *
     * @param  string  $series
     * @return ProductContract
     */
    public function setSeries(string $series): ProductContract
    {
        return $this->setAttribute('series', $series);
    }

    /**
     * Get the product series.
     *
     * @return string
     */
    public function getSeries(): string
    {
        return $this->getAttribute('series');
    }

    /**
     * Set the product type.
     *
     * @param  string  $type
     * @return ProductContract
     */
    public function setType(string $type): ProductContract
    {
        return $this->setAttribute('type', $type);
    }

    /**
     * Get the product type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getAttribute('type');
    }

    /**
     * Set the product sales unit.
     *
     * @param  string  $salesUnit
     * @return ProductContract
     */
    public function setSalesUnit(string $salesUnit): ProductContract
    {
        return $this->setAttribute('sales_unit', $salesUnit);
    }

    /**
     * Get the product sales unit.
     *
     * @return string
     */
    public function getSalesUnit(): string
    {
        return $this->getAttribute('sales_unit') ?: '';
    }

    /**
     * Set the supplier code.
     *
     * @param string $supplierCode
     * @return ProductContract
     */
    public function setSupplierCode(string $supplierCode): ProductContract
    {
        return $this->setAttribute('supplier_code', $supplierCode);
    }

    /**
     * Get the supplier code.
     *
     * @return string
     */
    public function getSupplierCode(): string
    {
        return $this->getAttribute('supplier_code');
    }

    /**
     * Get the searchable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();
        $searchableArray = [];

        foreach ($this->getSearchableFields() as $name => $field) {
            $values = [];
            $name = is_numeric($name) ? $field : $name;

            if (is_array($field)) {
                foreach ($field as $f) {
                    $values[] = $array[$f];
                }
            } else {
                $values = [ $array[$field] ];
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
    public function getSearchableFields(): array
    {
        return [
            'sku', 'group', 'description' => [ 'name', 'keywords' ], 'brand', 'series', 'type', 'ean', 'supplier_code',
        ];
    }

    /**
     * Create a product model from a GetProducts soap product.
     *
     * @param  \WTG\Soap\GetProducts\Response\Product  $product
     * @return Product
     */
    public static function createFromSoapProduct(\WTG\Soap\GetProducts\Response\Product $product): Product
    {
        /** @var static $model */
        $model = static::withTrashed()->firstOrNew([
            'sku' => $product->sku,
            'sales_unit' => $product->sales_unit
        ]);

        foreach (get_object_vars($product) as $key => $value) {
            if (in_array($key, $model->guarded)) {
                continue;
            }

            $model->setAttribute($key, $value);
        }

        return $model;
    }

    /**
     * Find a product by sku.
     *
     * @param string $sku
     * @param bool $fail
     * @return Product|null
     */
    public static function findBySku(string $sku, bool $fail = false): ?Product
    {
        /** @var Builder $query */
        $query = static::where('sku', $sku);

        return $fail ? $query->firstOrFail() : $query->first();
    }

    /**
     * Get the product image url.
     *
     * @param string $size
     * @return string
     * @throws \Exception
     */
    public function getImageUrl(string $size = self::IMAGE_SIZE_LARGE)
    {
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
            default:
                $size = self::IMAGE_SIZE_LARGE;
                $width = 300;
                $height = 300;
        }

        $path = sprintf("storage/uploads/images/products/%s.jpg", $this->getAttribute('sku'));
        $cacheKey = 'product-image-' . $this->getAttribute('sku') . '-' . $size;

        if (! file_exists(public_path($path))) {
            $path = static::IMAGE_PLACEHOLDER_PATH;
            $cacheKey = static::IMAGE_PLACEHOLDER_CACHE_KEY . '-' . $size;
        }

        return cache()->remember($cacheKey, 60 * 60 * 24, function () use ($path, $width, $height) {
            return Image::make($path)
                ->trim('top-left', null, 10, 5)
                ->resize($width, $height, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                })
                ->resizeCanvas($width, $height)
                ->encode('data-url');
        });
    }

    /**
     * Get the brand / series / type path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return sprintf(
            '%s  /  %s',
//            $this->getAttribute('brand'),
            $this->getAttribute('series'),
            $this->getAttribute('type')
        );
    }

    /**
     * Get the url for the product.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return '/product/' . $this->getSku();

        /** @var SeoUrl $seoUrl */
        /**$seoUrl = $this->getAttribute('seo_url');

        if (! $seoUrl) {
            $seoUrl = new SeoUrl;
            $seoUrl->target_path = 'product/' . $this->getSku();
            $seoUrl->is_redirect = false;
            $seoUrl->source_path = '/' . str_slug($this->getName());
            $seoUrl->product_id = $this->getId();
        }

        return $seoUrl->source_path;*/
    }

    /**
     * Is this product a pack product.
     *
     * @return bool
     */
    public function isPack(): bool
    {
        return null !== $this->getPack();
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
     * Get a pack instance.
     *
     * @return Pack|null
     */
    public function getPack(): ?Pack
    {
        return $this->getAttribute('pack');
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
     * Set the stock display.
     *
     * @param  string  $stockDisplay
     * @return ProductContract
     */
    public function setStockDisplay(string $stockDisplay): ProductContract
    {
        return $this->setAttribute('type', $stockDisplay);
    }

    /**
     * Get the stock display.
     *
     * @return string
     */
    public function getStockDisplay(): string
    {
        return $this->getAttribute('stock_display') ?: self::DEFAULT_STOCK_DISPLAY;
    }
}