<?php

declare(strict_types=1);

namespace WTG\Catalog\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WTG\Catalog\Api\Model\PriceFactorInterface;
use WTG\Models\Product;

/**
 * Price factor model.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PriceFactor extends Model implements PriceFactorInterface
{
    /**
     * @var string
     */
    protected $table = 'product_price_factors';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get related product.
     *
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->getAttribute('product');
    }

    /**
     * Set the related product.
     *
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): self
    {
        return $this->product()->associate($product);
    }

    /**
     * Product relation.
     *
     * @return BelongsTo
     */
    protected function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * @return null|string
     */
    public function getErpId(): ?string
    {
        return $this->getAttribute('erp_id');
    }

    /**
     * @param string $erpId
     * @return $this
     */
    public function setErpId(string $erpId): self
    {
        return $this->setAttribute('erp_id', $erpId);
    }

    /**
     * @return float
     */
    public function getPricePer(): float
    {
        return (float)$this->getAttribute('price_per');
    }

    /**
     * @param float $pricePer
     * @return $this
     */
    public function setPricePer(float $pricePer): self
    {
        return $this->setAttribute('price_per', $pricePer);
    }

    /**
     * @return float
     */
    public function getPriceFactor(): float
    {
        return (float)$this->getAttribute('price_factor');
    }

    /**
     * @param float $priceFactor
     * @return PriceFactor
     */
    public function setPriceFactor(float $priceFactor): self
    {
        return $this->setAttribute('price_factor', $priceFactor);
    }

    /**
     * @return string
     */
    public function getPriceUnit(): string
    {
        return $this->getAttribute('price_unit');
    }

    /**
     * @param string $priceUnit
     * @return PriceFactor
     */
    public function setPriceUnit(string $priceUnit): self
    {
        return $this->setAttribute('price_unit', $priceUnit);
    }

    /**
     * @return string
     */
    public function getScaleUnit(): string
    {
        return $this->getAttribute('scale_unit');
    }

    /**
     * @param string $scaleUnit
     * @return PriceFactor
     */
    public function setScaleUnit(string $scaleUnit): self
    {
        return $this->setAttribute('scale_unit', $scaleUnit);
    }
}
