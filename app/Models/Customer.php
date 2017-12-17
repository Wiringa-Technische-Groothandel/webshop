<?php

namespace WTG\Models;

use Illuminate\Support\Collection;
use WTG\Contracts\ContactContract;
use WTG\Contracts\ProductContract;
use WTG\Contracts\CustomerContract;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Customer model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Customer extends Authenticatable implements CustomerContract
{
    use SoftDeletes, HasRoles;

    const CUSTOMER_ROLE_SUPER_ADMIN = 'super-admin';
    const CUSTOMER_ROLE_ADMIN = 'admin';
    const CUSTOMER_ROLE_MANAGER = 'manager';
    const CUSTOMER_ROLE_USER = 'user';

    /**
     * @var string
     */
    protected $guard_name = 'web';

    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'password',
        'remember_token',
        'active'
    ];

    /**
     * Company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Contact relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    /**
     * Favorites relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    /**
     * Quote relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    /**
     * Related quote items through the quote.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quoteItems()
    {
        return $this->hasManyThrough(QuoteItem::class, Quote::class);
    }

    /**
     * Get the identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string
    {
        return $this->getAttribute('id');
    }

    /**
     * Get the contact.
     *
     * @return ContactContract
     */
    public function getContact(): ContactContract
    {
        $contact = $this->getAttribute('contact');

        if (! $contact) {
            /** @var Contact $contact */
            $contact = app()->make(ContactContract::class);
            $contact->setAttribute('customer_id', $this->identifier());
            $contact->save();
        }

        return $contact;
    }

    /**
     * Get the favorites.
     *
     * @return Collection
     */
    public function getFavorites(): Collection
    {
        return $this->getAttribute('favorites');
    }

    /**
     * Check if the customer has set the product as favorite.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function hasFavorite(ProductContract $product): bool
    {
        return $this->favorites()->where('product_id', $product->identifier())->exists();
    }

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function addFavorite(ProductContract $product): void
    {
        $this->favorites()->attach($product->identifier());
    }

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function removeFavorite(ProductContract $product): void
    {
        $this->favorites()->detach($product->identifier());
    }

    /**
     * Get the default address.
     *
     * @return Address|null
     */
    public function getDefaultAddress(): ?Address
    {
        return $this->getAttribute('company')->addresses()->where('is_default', true)->first();
    }

    /**
     * Get the current or a new quote for the customer.
     *
     * @return Quote
     */
    public function getActiveQuote(): Quote
    {
        $quote = $this->quote()->with(['items', 'items.product'])->first();

        if ($quote === null) {
            $quote = new Quote;
            $quote->setAttribute('customer_id', $this->getAttribute('id'));
            $quote->save();
        }

        return $quote;
    }

    /**
     * Set the default address.
     *
     * @param  int|Address  $address
     * @return bool
     */
    public function setDefaultAddress($address): bool
    {
        return $this->company->setDefaultAddress($address);
    }

    /**
     * Get the customer number.
     *
     * @return string
     */
    public function getCustomerNumber(): string
    {
        return $this->company->getAttribute('customer_number');
    }
}