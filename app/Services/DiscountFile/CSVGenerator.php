<?php

declare(strict_types=1);

namespace WTG\Services\DiscountFile;

use WTG\Models\Customer;
use WTG\Models\Discount;
use WTG\Services\DiscountFile\Api\Generator;

/**
 * CSV Generator.
 *
 * @package     WTG\Services
 * @subpackage  DiscountFile
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CSVGenerator extends AbstractGenerator implements Generator
{
    public const DELIMITER = ';';
    public const HEADER = "Artikelnr;Omschrijving;Kortingspercentage;ingangsdatum\r\n";

    /**
     * CSVGenerator constructor.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);

        $this->start_date = date('Y-m-d');
    }

    /**
     * @inheritdoc
     */
    public function addGroupDiscounts()
    {
        $discounts = Discount::where('company_id', $this->customer->getCompany()->getCustomerNumber())
            ->where('importance', Discount::IMPORTANCE_GROUP)
            ->where('group_desc', '!=', 'Vervallen')
            ->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateGroupDiscountLine($discount);
        }
    }

    /**
     * Generate the discount line for a group
     *
     * @param Discount $discount
     * @return string
     */
    public function generateGroupDiscountLine(Discount $discount)
    {
        $productNumber = $discount->product;
        $description = preg_replace("/[\r\n]*/", '', $discount->group_desc);
        $discountAmount = $discount->discount . '%';

        return $productNumber .
            static::DELIMITER .
            $description .
            static::DELIMITER .
            $discountAmount .
            static::DELIMITER .
            $this->start_date .
            "\r\n";
    }

    /**
     * @inheritdoc
     */
    public function addDefaultGroupDiscounts()
    {
        $discounts = Discount::where('importance', Discount::IMPORTANCE_GENERIC)
            ->where('group_desc', '!=', 'Vervallen')
            ->whereNotIn(
                'product',
                function ($query) {
                    $query->select('product')
                        ->from('discounts')
                        ->where('importance', Discount::IMPORTANCE_GROUP)
                        ->where('company_id', $this->customer->getCompany()->getCustomerNumber());
                }
            )
            ->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateGroupDiscountLine($discount);
        }
    }

    /**
     * @inheritdoc
     */
    public function addDefaultProductDiscounts()
    {
        $discounts = Discount::where('importance', Discount::IMPORTANCE_PRODUCT)
            ->whereNotIn(
                'product',
                function ($query) {
                    $query->select('product')
                        ->from('discounts')
                        ->where('importance', Discount::IMPORTANCE_CUSTOMER)
                        ->where('company_id', $this->customer->getCompany()->getCustomerNumber());
                }
            )
            ->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * Generate the discount line for a product
     *
     * @param Discount $discount
     * @return string
     */
    public function generateProductDiscountLine(Discount $discount)
    {
        $productNumber = $discount->product;
        $description = preg_replace("/[\r\n]*/", '', $discount->product_desc);
        $discountAmount = $discount->discount . '%';

        return $productNumber .
            static::DELIMITER .
            $description .
            static::DELIMITER .
            $discountAmount .
            static::DELIMITER .
            $this->start_date .
            "\r\n";
    }

    /**
     * @inheritdoc
     */
    public function addProductDiscounts()
    {
        $discounts = Discount::where('company_id', $this->customer->getCompany()->getCustomerNumber())
            ->where('importance', Discount::IMPORTANCE_CUSTOMER)
            ->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * Prepend the header
     *
     * @return void
     */
    public function prependHeaderLine()
    {
        $this->text = static::HEADER . $this->text;
    }
}
