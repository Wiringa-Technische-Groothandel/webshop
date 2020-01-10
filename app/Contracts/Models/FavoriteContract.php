<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use WTG\Catalog\Api\Model\ProductInterface;

/**
 * Favorite contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface FavoriteContract
{
    /**
     * Get the product.
     *
     * @return null|ProductInterface
     */
    public function getProduct(): ?ProductInterface;

    /**
     * Get the customer.
     *
     * @return null|CustomerContract
     */
    public function getCustomer(): ?CustomerContract;
}
