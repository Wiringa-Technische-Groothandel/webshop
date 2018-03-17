<?php

namespace WTG\Services\DiscountFile;

use WTG\Models\Customer;

/**
 * CSV Generator.
 *
 * @package     WTG\Services
 * @subpackage  DiscountFile
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CSVGenerator extends AbstractGenerator implements Generator
{
    const DELIMITER = ';';
    const HEADER = "Artikelnr;Omschrijving;Kortingspercentage;ingangsdatum\r\n";

    /**
     * CSVGenerator constructor.
     *
     * @param  Customer  $customer
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
        $query = \DB::table('discounts')
            ->where('User_id', $this->customer)
            ->where('table', static::GROUP_DISCOUNT_TABLE)
            ->where('group_desc', '!=', 'Vervallen');

        $discounts = $query->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateGroupDiscountLine($discount);
        }
    }

    /**
     * @inheritdoc
     */
    public function addDefaultGroupDiscounts()
    {
        $query = \DB::table('discounts')
            ->where('table', static::DEFAULT_GROUP_DISCOUNT_TABLE)
            ->where('group_desc', '!=', 'Vervallen')
            ->whereNotIn('product', function ($query) {
                $query->select('product')
                    ->from('discounts')
                    ->where('table', static::GROUP_DISCOUNT_TABLE)
                    ->where('User_Id', $this->customer);
            });

        $discounts = $query->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateGroupDiscountLine($discount);
        }
    }

    /**
     * @inheritdoc
     */
    public function addDefaultProductDiscounts()
    {
        $query = \DB::table('discounts')
            ->where('table', static::DEFAULT_PRODUCT_DISCOUNT_TABLE)
            ->whereNotIn('product', function ($query) {
                $query->select('product')
                    ->from('discounts')
                    ->where('table', static::PRODUCT_DISCOUNT_TABLE)
                    ->where('User_Id', $this->customer);
            });

        $discounts = $query->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * @inheritdoc
     */
    public function addProductDiscounts()
    {
        $query = \DB::table('discounts')
            ->where('User_id', $this->customer)
            ->where('table', static::PRODUCT_DISCOUNT_TABLE);

        $discounts = $query->get();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * Generate the discount line for a group
     *
     * @param  \stdClass  $discount
     * @return string
     */
    public function generateGroupDiscountLine(\stdClass $discount)
    {
        $productNumber = $discount->product;
        $description = preg_replace("/[\r\n]*/", '', $discount->group_desc);
        $discountAmount = $discount->discount.'%';

        return $productNumber.
            static::DELIMITER.
            $description.
            static::DELIMITER.
            $discountAmount.
            static::DELIMITER.
            $this->start_date.
            "\r\n";
    }

    /**
     * Generate the discount line for a product
     *
     * @param  \stdClass  $discount
     * @return string
     */
    public function generateProductDiscountLine(\stdClass $discount)
    {
        $productNumber = $discount->product;
        $description = preg_replace("/[\r\n]*/", '', $discount->product_desc);
        $discountAmount = $discount->discount.'%';

        return $productNumber.
            static::DELIMITER.
            $description.
            static::DELIMITER.
            $discountAmount.
            static::DELIMITER.
            $this->start_date.
            "\r\n";
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