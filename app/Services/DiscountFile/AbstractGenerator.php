<?php

declare(strict_types=1);

namespace WTG\Services\DiscountFile;

use WTG\Models\Customer;

/**
 * Abstract generator.
 *
 * @package     WTG\Services
 * @subpackage  DiscountFile
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class AbstractGenerator implements Generator
{
    const GROUP_DISCOUNT_TABLE = 'VA-220';
    const PRODUCT_DISCOUNT_TABLE = 'VA-260';
    const DEFAULT_GROUP_DISCOUNT_TABLE = 'VA-221';
    const DEFAULT_PRODUCT_DISCOUNT_TABLE = 'VA-261';

    /**
     * @var string
     */
    protected $text = '';

    /**
     * @var int
     */
    protected $customer;

    /**
     * @var string
     */
    protected $start_date;

    /**
     * CSVFileGenerator constructor.
     *
     * @param  Customer  $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Run the generator
     *
     * @return string
     */
    public function generate()
    {
        $this->addGroupDiscounts();

        $this->addDefaultGroupDiscounts();

        $this->addDefaultProductDiscounts();

        $this->addProductDiscounts();

        $this->prependHeaderLine();

        return $this->text;
    }
}