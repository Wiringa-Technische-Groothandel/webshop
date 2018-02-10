<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\DescriptionContract;

/**
 * Description model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Description extends Model implements DescriptionContract
{
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
     * Get the related product.
     *
     * @return ProductContract
     */
    public function getProduct(): ProductContract
    {
        return $this->getAttribute('product');
    }

    /**
     * Get the description identifier.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the description value.
     *
     * @param  string  $value
     * @return DescriptionContract
     */
    public function setValue(string $value): DescriptionContract
    {
        return $this->setAttribute('value', $value);
    }

    /**
     * Get the description value.
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        $string = $this->getAttribute('value');

        return $string ? new HtmlString($string) : null;
    }
}