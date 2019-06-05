<?php

namespace WTG\Models;

use Illuminate\Support\Collection;
use WTG\Notifications\ResetPassword;
use WTG\Contracts\Models\RoleContract;
use Illuminate\Notifications\Notifiable;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\ContactContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CustomerContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Customer model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Customer extends Authenticatable implements CustomerContract
{
    use SoftDeletes, Notifiable;

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
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected $with = [
        'contact'
    ];

    /**
     * Get the password reset email.
     *
     * @return string|null
     */
    public function getEmailForPasswordReset(): ?string
    {
        return $this->getContact()->getContactEmail();
    }

    /**
     * @return string|null
     */
    public function routeNotificationForMail()
    {
        return $this->getContact()->getContactEmail();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Role relation.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Contact relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contact(): HasOne
    {
        return $this->hasOne(Contact::class);
    }

    /**
     * Favorites relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    /**
     * Quote relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quote(): HasOne
    {
        return $this->hasOne(Quote::class);
    }

    /**
     * Related quote items through the quote.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quoteItems(): HasManyThrough
    {
        return $this->hasManyThrough(QuoteItem::class, Quote::class);
    }

    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getId(): ?string
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
            $contact->setAttribute('customer_id', $this->getId());
            $contact->save();
        }

        return $contact;
    }

    /**
     * Set the username.
     *
     * @param  string  $username
     * @return CustomerContract
     */
    public function setUsername(string $username): CustomerContract
    {
        return $this->setAttribute('username', $username);
    }

    /**
     * Get the username.
     *
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->getAttribute('username');
    }

    /**
     * Set the password.
     *
     * @param  string  $password
     * @return CustomerContract
     */
    public function setPassword(string $password): CustomerContract
    {
        return $this->setAttribute('password', $password);
    }

    /**
     * Get the password.
     *
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->getAttribute('password');
    }

    /**
     * Set the active.
     *
     * @param  bool  $active
     * @return CustomerContract
     */
    public function setActive(bool $active): CustomerContract
    {
        return $this->setAttribute('active', $active);
    }

    /**
     * Get the active.
     *
     * @return bool
     */
    public function getActive(): bool
    {
        return (bool) $this->getAttribute('active');
    }

    /**
     * Get the company.
     *
     * @return CompanyContract
     */
    public function getCompany(): CompanyContract
    {
        return $this->getAttribute('company');
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
        return $this->favorites()->where('product_id', $product->getId())->exists();
    }

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function addFavorite(ProductContract $product): void
    {
        $this->favorites()->attach($product->getId());
    }

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function removeFavorite(ProductContract $product): void
    {
        $this->favorites()->detach($product->getId());
    }

    /**
     * Set the customer role.
     *
     * @param  RoleContract  $role
     * @return CustomerContract
     */
    public function setRole(RoleContract $role): CustomerContract
    {
        return $this->setAttribute('role_id', $role->getId());
    }

    /**
     * Get the customer role.
     *
     * @return RoleContract
     */
    public function getRole(): RoleContract
    {
        return $this->getAttribute('role');
    }
}