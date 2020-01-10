<?php

declare(strict_types=1);

namespace WTG\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use WTG\Catalog\Model\Product;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\Account\AddressServiceContract;
use WTG\Exceptions\CartUpdateException;

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
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the cart address.
     *
     * @return null|AddressContract
     * @throws BindingResolutionException
     */
    public function getAddress(): ?AddressContract
    {
        /** @var AddressServiceContract $addressService */
        $addressService = app()->make(AddressServiceContract::class);
        $customer = $this->getAttribute('customer');
        $address = $this->getAttribute('address') ?: $addressService->getDefaultAddressForCustomer($customer);

        if (! $address) {
            $address = $addressService->getPickupAddress();
        }

        return $address;
    }

    /**
     * Set the delivery address.
     *
     * @param AddressContract $address
     * @return CartContract
     */
    public function setAddress(AddressContract $address): CartContract
    {
        $this->address()->associate($address);

        return $this;
    }

    /**
     * Address relation.
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Set the finished at timestamp.
     *
     * @param Carbon $carbon
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
     * @param CustomerContract $customer
     * @return CartContract
     */
    public function loadForCustomer(CustomerContract $customer): CartContract
    {
        $this->forceFill(
            $this->firstOrCreate(
                [
                    'customer_id' => $customer->getId(),
                    'finished_at' => null,
                ]
            )->toArray()
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
     * @param Product $product
     * @param float $quantity
     * @return CartItemContract
     * @throws CartUpdateException
     * @throws BindingResolutionException
     */
    public function addProduct(Product $product, float $quantity = 1.0): CartItemContract
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
     * Find a cart item by product.
     *
     * @param Product $product
     * @return null|CartItemContract
     */
    public function findProduct(Product $product): ?CartItemContract
    {
        return $this->items()->where('product_id', $product->getId())->first();
    }

    /**
     * Quote item relation.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Throw a cart update exception.
     *
     * @return void
     * @throws CartUpdateException
     */
    protected function throwFailedCartException(): void
    {
        throw new CartUpdateException('An error occurred while saving a cart.');
    }

    /**
     * Update a cart item.
     *
     * @param Product $product
     * @param float $quantity
     * @return CartItemContract
     * @throws CartUpdateException
     */
    public function updateProduct(Product $product, float $quantity): CartItemContract
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
     * @param Product $product
     * @return bool
     * @throws Exception
     */
    public function removeProduct(Product $product): bool
    {
        /** @var CartItemContract|Model $item */
        $item = $this->findProduct($product);

        if (! $item) {
            return true;
        }

        return $item->delete();
    }

    /**
     * Check if the product is in the cart.
     *
     * @param Product $product
     * @return bool
     */
    public function hasProduct(Product $product): bool
    {
        return (bool)$this->findProduct($product);
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
}
