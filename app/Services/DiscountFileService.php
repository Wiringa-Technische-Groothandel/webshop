<?php

declare(strict_types=1);

namespace WTG\Services;

use WTG\Contracts\Models\CustomerContract;
use WTG\Exceptions\InvalidFormatException;
use WTG\Models\Customer;
use WTG\Services\DiscountFile\CSVGenerator;
use WTG\Services\DiscountFile\ICCGenerator;

/**
 * Discount file service.
 *
 * @package     WTG
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DiscountFileService
{
    public const FORMAT_TYPE_ICC = 'icc';
    public const FORMAT_TYPE_CSV = 'csv';

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * DiscountFileService constructor.
     *
     * @param CustomerContract $customer
     */
    public function __construct(CustomerContract $customer = null)
    {
        $this->customer = $customer;
    }

    /**
     * Set the customer.
     *
     * @param CustomerContract $customer
     * @return $this
     */
    public function setCustomer(CustomerContract $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Generate the discount data.
     *
     * @param string $format
     * @return string
     * @throws InvalidFormatException
     */
    public function generateData(string $format): string
    {
        if ($format === self::FORMAT_TYPE_ICC) {
            $discountData = $this->generateICC();
        } elseif ($format === self::FORMAT_TYPE_CSV) {
            $discountData = $this->generateCSV();
        } else {
            throw new InvalidFormatException(__("Invalid file format."));
        }

        return $discountData;
    }

    /**
     * Generate an ICC file.
     *
     * @return string
     */
    public function generateICC(): string
    {
        /** @var ICCGenerator $generator */
        $generator = new ICCGenerator($this->customer);

        return $generator->generate();
    }

    /**
     * Generate a CSV file.
     *
     * @return string
     */
    public function generateCSV(): string
    {
        /** @var CSVGenerator $generator */
        $generator = new CSVGenerator($this->customer);

        return $generator->generate();
    }
}
