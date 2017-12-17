<?php

namespace WTG\Contracts;

/**
 * Product contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface ProductContract
{
    /**
     * Get or set the product identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string;

    /**
     * Get or set the product sku.
     *
     * @param  null|string  $sku
     * @return string
     */
    public function sku(?string $sku = null): string;

    /**
     * Get or set the product group.
     *
     * @param  null|string  $group
     * @return string
     */
    public function group(?string $group = null): string;

    /**
     * Get or set the product sales unit.
     *
     * @param  null|string  $salesUnit
     * @return string
     */
    public function salesUnit(?string $salesUnit = null): string;
}