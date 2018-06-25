<?php

namespace WTG\Converters;

use WTG\Models\Quote;
use WTG\Models\Contact;
use WTG\Models\Product;
use WTG\Models\Customer;
use WTG\Models\QuoteItem;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\CompanyContract;

/**
 * Customer table converter.
 *
 * @package     WTG\Converters
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CustomerTableConverter extends AbstractTableConverter
{
    /**
     * @var array
     */
    protected $csvFields = [
        'id',
        'username',
        'company_id',
        'email',
        'isAdmin',
        'manager',
        'password',
        'favorites',
        'cart',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * Create a new entity.
     *
     * @param  array  $data
     * @return Model|null
     */
    public function createModel(array $data): ?Model
    {
        $company = app()->make(CompanyContract::class)->where('customer_number', $data['company_id'])->first();

        if ($company === null) {
            \Log::warning('[Customer table conversion] No company was found for customer number '.$data['company_id']);

            return null;
        }

        $customer = new Customer;

        $customer->setAttribute('company_id', $company->getId());
        $customer->setAttribute('username', $data['username']);
        $customer->setAttribute('password', $data['password']);

        $customer->save();

        $this->createContact($customer, $data);

        if ($data['cart'] && $data['cart'] !== "NULL") {
            $this->createQuote($customer, $data['cart']);
        }

        $customer->assignRole($this->determineRole($data));

        return null;
    }

    /**
     * Create a new contact for a customer.
     *
     * @param  Customer  $customer
     * @param  array  $data
     * @return Contact
     */
    protected function createContact(Customer $customer, array $data): Contact
    {
        $contact = new Contact;

        $contact->setAttribute('customer_id', $customer->getAttribute('id'));
        $contact->setAttribute('contact_email', $data['email']);

        $contact->save();

        return $contact;
    }

    /**
     * Create a quote.
     *
     * @param  Customer  $customer
     * @param  string  $cartData
     * @return null|Quote
     */
    protected function createQuote(Customer $customer, string $cartData): ?Quote
    {
        try {
            $cart = unserialize($cartData);
        } catch (\Exception $e) {
            dd($e, $cartData);

            return null;
        }

        if ($cart === false) {
            \Log::warning(sprintf("[Customer table conversion] Customer %s had an invalid cart.", $customer->getAttribute('id')));

            return null;
        }

        $quote = new Quote;

        $quote->setAttribute('customer_id', $customer->getAttribute('id'));

        $quote->save();

        foreach ($cart as $item) {
            $this->addItemToQuote($quote, $item);
        }

        return $quote;
    }

    /**
     * Create a quote item.
     *
     * @param  Quote  $quote
     * @param  array  $item
     * @return QuoteItem|null
     */
    protected function addItemToQuote(Quote $quote, array $item): ?QuoteItem
    {
        /** @var Product|null $product */
        $product = Product::where('sku', $item['id'])->first();

        if (!$product) {
            \Log::warning(sprintf("[Customer table conversion] Customer %s had a non-existent product (%s) in their cart", $quote->getAttribute('customer_id'), $item['id']));

            return null;
        }

        $quoteItem = new QuoteItem;

        $quoteItem->setAttribute('quote_id', $quote->getAttribute('id'));
        $quoteItem->setAttribute('product_id', $product->getAttribute('id'));
        $quoteItem->setAttribute('qty', $item['qty']);

        $quoteItem->save();

        return $quoteItem;
    }

    /**
     * Determine the role of the customer.
     *
     * @param  array  $data
     * @return string
     */
    private function determineRole(array $data)
    {
        if ($data['isAdmin']) {
            return Customer::CUSTOMER_ROLE_SUPER_ADMIN;
        }

        if ($data['company_id'] === $data['username']) {
            return Customer::CUSTOMER_ROLE_ADMIN;
        }

        if ($data['manager'] === "1") {
            return Customer::CUSTOMER_ROLE_MANAGER;
        }

        if ($data['manager'] === "0") {
            return Customer::CUSTOMER_ROLE_USER;
        }
    }
}