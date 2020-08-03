<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Catalog\Api\ProductDescriptionInterface;
use WTG\Catalog\Traits\BelongsToProduct;
use WTG\Foundation\Traits\HasId;

/**
 * Product description model.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductDescription extends Model implements ProductDescriptionInterface
{
    use HasId;
    use BelongsToProduct;

    /**
     * @param string $value
     * @return ProductDescriptionInterface
     */
    public function setValue(string $value): ProductDescriptionInterface
    {
        return $this->setAttribute(self::FIELD_VALUE, $value);
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->getAttribute(self::FIELD_VALUE);
    }
}
