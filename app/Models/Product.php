<?php

namespace WTG\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * @var array
     */
    protected $guarded = ['id', 'webshop'];

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
     * @return string
     */
    public function getId(): string
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
        return $this->getAttribute('sku');
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
        return $this->getAttribute('group');
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
        return $this->getAttribute('sales_unit');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $searchableArray = [
            'sku'       => $array['sku'],
            'group'     => $array['group'],
            'name'      => $array['name'],
            'keywords'  => $array['keywords'],
            'brand'     => $array['brand'],
            'series'    => $array['series'],
            'type'      => $array['type']
        ];

        return $searchableArray;
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
     * @param  string  $sku
     * @return Product|null
     */
    public static function findBySku(string $sku): ?Product
    {
        return static::where('sku', $sku)->first();
    }

    /**
     * Get the product image url.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return asset(
            sprintf("img/products/%s.jpg", $this->getAttribute('sku'))
        );
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
}