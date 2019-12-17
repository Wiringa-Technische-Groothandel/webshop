<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

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
     * @return null|ProductContract
     */
    public function getProduct(): ?ProductContract;

    /**
     * Get the customer.
     *
     * @return null|CustomerContract
     */
    public function getCustomer(): ?CustomerContract;
}
