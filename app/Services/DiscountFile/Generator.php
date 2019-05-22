<?php

namespace WTG\Services\DiscountFile;

/**
 * Interface Generator
 *
 * @package     WTG\Services
 * @subpackage  DiscountFile
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface Generator
{
    /**
     * Generate the file contents
     *
     * @return string
     */
    public function generate();

    /**
     * Add the customer bound group discounts
     *
     * @return void
     */
    public function addGroupDiscounts();

    /**
     * Add the default group discounts
     *
     * @return void
     */
    public function addDefaultGroupDiscounts();

    /**
     * Add the default product discounts
     *
     * @return void
     */
    public function addDefaultProductDiscounts();

    /**
     * Add the customer bound product discounts
     *
     * @return void
     */
    public function addProductDiscounts();
}