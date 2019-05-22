<?php

namespace WTG\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use WTG\Contracts\Models\CartContract;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Services\Account\AddressServiceContract;
use WTG\Exceptions\CartUpdateException;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Quote model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Quote extends Model implements CartContract
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Customer relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Quote item relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Address relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Get the cart address.
     *
     * @return null|AddressContract
     */
    public function getAddress(): ?AddressContract
    {
        /** @var AddressServiceContract $addressService */
        $addressService = app()->make(AddressServiceContract::class);
        $customer = $this->getAttribute('customer');
        $address = $this->getAttribute('address') ?: $addressService->getDefaultAddressForCustomer($customer);

        if (! $address) {
            $address = $addressService->getDefaultAddress();
        }

        return $address;
    }

    /**
     * Set the delivery address.
     *
     * @param  AddressContract  $address
     * @return CartContract
     */
    public function setAddress(AddressContract $address): CartContract
    {
        $this->address()->associate($address);

        return $this;
    }

    /**
     * Set the finished at timestamp.
     *
     * @param  Carbon  $carbon
     * @return CartContract
     */
    public function setFinishedAt(Carbon $carbon): CartContract
    {
        return $this->setAttribute('finished_at', $carbon);
    }

    /**
     * Get the finished at timestamp.
     *
     * @return null|Carbon
     */
    public function getFinishedAt(): ?Carbon
    {
        return $this->getAttribute('finished_at');
    }

    /**
     * Find or create a cart for a customer.
     *
     * @param  CustomerContract  $customer
     * @return CartContract
     */
    public function loadForCustomer(CustomerContract $customer): CartContract
    {
        $this->forceFill(
            $this->firstOrCreate([
                'customer_id' => $customer->getId(),
                'finished_at' => null
            ])->toArray()
        );

        $this->exists = true;

        return $this;
    }

    /**
     * Get the product identifier.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Add a product to the cart.
     *
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return CartItemContract
     * @throws CartUpdateException
     */
    public function addProduct(ProductContract $product, float $quantity = 1.0): CartItemContract
    {
        $item = $this->findProduct($product);

        if ($item) {
            $item->setQuantity($quantity + $item->getQuantity());
        } else {
            /** @var CartItemContract|Model $item */
            $item = app()->make(CartItemContract::class);
            $item->setProduct($product);
            $item->setQuantity($quantity);
            $item->setCart($this);
        }

        if ($item->save()) {
            // Update model timestamp to indicate the cart was updated.
            $this->touch();

            return $item;
        }

        $this->throwFailedCartException();
    }

    /**
     * Update a cart item.
     *
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return CartItemContract
     * @throws CartUpdateException
     */
    public function updateProduct(ProductContract $product, float $quantity): CartItemContract
    {
        /** @var CartItemContract|Model $item */
        $item = $this->findProduct($product);
        $item->setQuantity($quantity);

        if ($item->save()) {
            // Update model timestamp to indicate the cart was updated.
            $this->touch();

            return $item;
        }

        $this->throwFailedCartException();
    }

    /**
     * Remove a product from the cart.
     *
     * @param  ProductContract  $product
     * @return bool
     * @throws \Exception
     */
    public function removeProduct(ProductContract $product): bool
    {
        /** @var CartItemContract|Model $item */
        $item = $this->findProduct($product);

        if (! $item) {
            return true;
        }

        return $item->delete();
    }

    /**
     * Find a cart item by product.
     *
     * @param  ProductContract  $product
     * @return null|CartItemContract
     */
    public function findProduct(ProductContract $product): ?CartItemContract
    {
        return $this->items()->where('product_id', $product->getId())->first();
    }

    /**
     * Check if the product is in the cart.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function hasProduct(ProductContract $product): bool
    {
        return (bool) $this->findProduct($product);
    }

    /**
     * Cart item collection.
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items()->with('product')->get();
    }

    /**
     * Cart item count.
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->items()->count();
    }

    /**
     * Throw a cart update exception.
     *
     * @throws CartUpdateException
     * @return void
     */
    protected function throwFailedCartException(): void
    {
        throw new CartUpdateException('An error occurred while saving a cart.');
    }
}