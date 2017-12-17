<?php

namespace WTG\Models;

use Laravel\Scout\Searchable;
use WTG\Contracts\ProductContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    protected $guarded = ['id'];

    /**
     * Product identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string
    {
        return $this->getAttribute('id');
    }

    /**
     * Get or set the sku.
     *
     * @param  null|string  $sku
     * @return string
     */
    public function sku(?string $sku = null): string
    {
        if ($sku) {
            $this->setAttribute('sku', $sku);
        }

        return $this->getAttribute('sku');
    }

    /**
     * Get or set the product group.
     *
     * @param  null|string  $group
     * @return string
     */
    public function group(?string $group = null): string
    {
        if ($group) {
            $this->setAttribute('group', $group);
        }

        return $this->getAttribute('group');
    }

    /**
     * Get or set the product sales unit.
     *
     * @param  null|string  $salesUnit
     * @return string
     */
    public function salesUnit(?string $salesUnit = null): string
    {
        if ($salesUnit) {
            $this->setAttribute('sales_unit', $salesUnit);
        }

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
            'sku' => $product->sku
        ]);

        foreach (get_object_vars($product) as $key => $value) {
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