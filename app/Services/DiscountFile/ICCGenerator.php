<?php

declare(strict_types=1);

namespace WTG\Services\DiscountFile;

use WTG\Models\Customer;
use WTG\Models\Discount;

/**
 * ICC generator.
 *
 * @package     WTG\Services
 * @subpackage  DiscountFile
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ICCGenerator extends AbstractGenerator implements Generator
{
    const GLN = 8714253038995;
    const END_DATE = 99991231;
    const DISCOUNT_2 = '00000';
    const DISCOUNT_3 = '00000';
    const FILE_VERSION = '1.1  ';
    const NET_PRICE = '000000000';
    const SMALL_SPACING = '       ';
    const LARGE_SPACING = '               ';

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var string
     */
    protected $name;

    /**
     * ICCGenerator constructor.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);

        $this->start_date = date('Ymd');
        $this->name = str_pad(str_limit($customer->getCompany()->getName(), 60, ''), 60);
    }

    /**
     * Add the group discounts to the file
     *
     * @return void
     */
    public function addGroupDiscounts()
    {
        $discounts = Discount::where('company_id', $this->customer->getCompany()->getCustomerNumber())
            ->where('importance', Discount::IMPORTANCE_GROUP)
            ->where('group_desc', '!=', 'Vervallen')
            ->get();

        $this->count += $discounts->count();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateGroupDiscountLine($discount);
        }
    }

    /**
     * Generate a line for a group discount
     *
     * @param Discount $discount
     * @return string
     */
    public function generateGroupDiscountLine(Discount $discount)
    {
        $groupNumber = str_pad($discount->product, 20);
        $productNumber = str_pad("", 20);
        $description = str_pad(
            preg_replace("/[\r\n]*/", '', $discount->group_desc),
            50
        );
        $discountAmount = ($discount->discount < 10 ? '00' : '0') . preg_replace("/\./", '', $discount->discount);
        $discountAmount = str_pad($discountAmount, 5, '0');

        return $groupNumber .
            $productNumber .
            $description .
            $discountAmount .
            static::DISCOUNT_2 .
            static::DISCOUNT_3 .
            static::NET_PRICE .
            $this->start_date .
            static::END_DATE .
            "\r\n";
    }

    /**
     * Add the default group bound discounts
     *
     * @return void
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

        $this->count += $discounts->count();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateGroupDiscountLine($discount);
        }
    }

    /**
     * Add the default product bound discounts
     *
     * @return void
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

        $this->count += $discounts->count();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * Generate a product discount line
     *
     * @param Discount $discount
     * @return string
     */
    public function generateProductDiscountLine(Discount $discount)
    {
        $groupNumber = str_pad("", 20);
        $productNumber = str_pad($discount->product, 20);
        $description = str_pad(
            preg_replace("/[\r\n]*/", '', $discount->product_desc),
            50
        );
        $discountAmount = ($discount->discount < 10 ? '00' : '0') . preg_replace("/\./", '', $discount->discount);
        $discountAmount = str_pad($discountAmount, 5, '0');

        return $groupNumber .
            $productNumber .
            $description .
            $discountAmount .
            static::DISCOUNT_2 .
            static::DISCOUNT_3 .
            static::NET_PRICE .
            $this->start_date .
            static::END_DATE .
            "\r\n";
    }

    /**
     * Add the customer bound product discounts
     *
     * @return void
     */
    public function addProductDiscounts()
    {
        $discounts = Discount::where('company_id', $this->customer->getCompany()->getCustomerNumber())
            ->where('importance', Discount::IMPORTANCE_CUSTOMER)
            ->get();

        $this->count += $discounts->count();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * Prepend the first line
     *
     * @return void
     */
    public function prependHeaderLine()
    {
        $this->text = str_pad(
            static::GLN .
                static::SMALL_SPACING .
                $this->customer->getCompany()->getCustomerNumber() .
                static::LARGE_SPACING .
                $this->start_date .
                sprintf("%'06d", $this->count) .
                static::FILE_VERSION .
                $this->name,
            130
        ) . "\r\n" .
            $this->text;
    }
}
