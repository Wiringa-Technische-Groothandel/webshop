<?php

namespace WTG\Services\DiscountFile;

use WTG\Models\Discount;
use WTG\Models\Customer;

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
     * @param  Customer  $customer
     */
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);

        $this->start_date = date('Ymd');
        $this->name = str_pad($customer->company->getAttribute('name'), 70, ' ', STR_PAD_RIGHT);
    }

    /**
     * Add the group discounts to the file
     *
     * @return void
     */
    public function addGroupDiscounts()
    {
        $discounts = Discount::where('company_id', $this->customer->getAttribute('company_id'))
            ->where('importance', Discount::IMPORTANCE_GROUP)
            ->where('group_desc', '!=', 'Vervallen')
            ->get();

        $this->count += $discounts->count();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateGroupDiscountLine($discount);
        }
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
            ->whereNotIn('product', function ($query) {
                $query->select('product')
                    ->from('discounts')
                    ->where('importance', Discount::IMPORTANCE_GROUP)
                    ->where('company_id', $this->customer->getAttribute('company_id'));
            })
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
            ->whereNotIn('product', function ($query) {
                $query->select('product')
                    ->from('discounts')
                    ->where('importance', Discount::IMPORTANCE_CUSTOMER)
                    ->where('company_id', $this->customer->getAttribute('company_id'));
            })
            ->get();

        $this->count += $discounts->count();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * Add the customer bound product discounts
     *
     * @return void
     */
    public function addProductDiscounts()
    {
        $discounts = Discount::where('company_id', $this->customer->getAttribute('company_id'))
            ->where('importance', Discount::IMPORTANCE_CUSTOMER)
            ->get();

        $this->count += $discounts->count();

        foreach ($discounts as $discount) {
            $this->text .= $this->generateProductDiscountLine($discount);
        }
    }

    /**
     * Generate a line for a group discount
     *
     * @param  Discount  $discount
     * @return string
     */
    public function generateGroupDiscountLine(Discount $discount)
    {
        $groupNumber = str_pad($discount->product, 20, ' ', STR_PAD_RIGHT);
        $productNumber = str_pad("", 20, ' ', STR_PAD_RIGHT);
        $description = str_pad(
            preg_replace("/[\r\n]*/", '', $discount->group_desc),
            50, ' ', STR_PAD_RIGHT
        );
        $discountAmount = ($discount->discount < 10 ? '00' : '0').preg_replace("/\./", '', $discount->discount);
        $discountAmount = str_pad($discountAmount, 5, '0', STR_PAD_RIGHT);

        return $groupNumber.
            $productNumber.
            $description.
            $discountAmount.
            static::DISCOUNT_2.
            static::DISCOUNT_3.
            static::NET_PRICE.
            $this->start_date.
            static::END_DATE.
            "\r\n";
    }

    /**
     * Generate a product discount line
     *
     * @param  Discount  $discount
     * @return string
     */
    public function generateProductDiscountLine(Discount $discount)
    {
        $groupNumber = str_pad("", 20, ' ', STR_PAD_RIGHT);
        $productNumber = str_pad($discount->product, 20, ' ', STR_PAD_RIGHT);
        $description = str_pad(
            preg_replace("/[\r\n]*/", '', $discount->product_desc),
            50, ' ', STR_PAD_RIGHT
        );
        $discountAmount = ($discount->discount < 10 ? '00' : '0').preg_replace("/\./", '', $discount->discount);
        $discountAmount = str_pad($discountAmount, 5, '0', STR_PAD_RIGHT);

        return $groupNumber.
            $productNumber.
            $description.
            $discountAmount.
            static::DISCOUNT_2.
            static::DISCOUNT_3.
            static::NET_PRICE.
            $this->start_date.
            static::END_DATE.
            "\r\n";
    }

    /**
     * Prepend the first line
     *
     * @return void
     */
    public function prependHeaderLine()
    {
        $this->text = static::GLN.
            static::SMALL_SPACING.
            $this->customer->company->getAttribute('name').
            static::LARGE_SPACING.
            $this->start_date.
            sprintf("%'06d", $this->count).
            static::FILE_VERSION.
            $this->name.
            "\r\n".
            $this->text;
    }
}