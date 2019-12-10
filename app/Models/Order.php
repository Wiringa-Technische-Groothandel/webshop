<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

use Illuminate\Support\Str;
use Knp\Snappy\GeneratorInterface;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\OrderContract;

/**
 * Order model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Order extends Model implements OrderContract
{
    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (self $model) {
            if (! $model->getUuid()) {
                $model->setUuid(Str::uuid()->toString());
            }
        });
    }

    /**
     * Company relation.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Order item relation.
     *
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
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
     * Get the order items.
     *
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items()->get();
    }

    /**
     * Set the customer number.
     *
     * @param  string  $customerNumber
     * @return OrderContract
     */
    public function setCustomerNumber(string $customerNumber): OrderContract
    {
        return $this->setAttribute('customer_number', $customerNumber);
    }

    /**
     * Get the customer number.
     *
     * @return null|string
     */
    public function getCustomerNumber(): ?string
    {
        return $this->getAttribute('customer_number');
    }

    /**
     * Set the company.
     *
     * @param  CompanyContract  $company
     * @return OrderContract
     */
    public function setCompany(CompanyContract $company): OrderContract
    {
        $this->company()->associate($company);

        return $this;
    }

    /**
     * Get the company.
     *
     * @return null|CompanyContract
     */
    public function getCompany(): ?CompanyContract
    {
        return $this->getAttribute('company');
    }

    /**
     * Set the name.
     *
     * @param  string  $name
     * @return OrderContract
     */
    public function setName(string $name): OrderContract
    {
        return $this->setAttribute('name', $name);
    }

    /**
     * Get the name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }

    /**
     * Set the street.
     *
     * @param  string  $street
     * @return OrderContract
     */
    public function setStreet(string $street): OrderContract
    {
        return $this->setAttribute('street', $street);
    }

    /**
     * Get the street.
     *
     * @return null|string
     */
    public function getStreet(): ?string
    {
        return $this->getAttribute('street');
    }

    /**
     * Set the postcode.
     *
     * @param  null|string  $postcode
     * @return OrderContract
     */
    public function setPostcode(string $postcode): OrderContract
    {
        return $this->setAttribute('postcode', $postcode);
    }

    /**
     * Get the postcode.
     *
     * @return null|string
     */
    public function getPostcode(): ?string
    {
        return $this->getAttribute('postcode');
    }

    /**
     * Set the city.
     *
     * @param  null|string  $city
     * @return OrderContract
     */
    public function setCity(string $city): OrderContract
    {
        return $this->setAttribute('city', $city);
    }

    /**
     * Get the city.
     *
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->getAttribute('city');
    }

    /**
     * Set the comment.
     *
     * @param  null|string  $comment
     * @return OrderContract
     */
    public function setComment(?string $comment): OrderContract
    {
        return $this->setAttribute('comment', $comment);
    }

    /**
     * Get the comment.
     *
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->getAttribute('comment');
    }

    /**
     * Order total.
     *
     * @return float
     */
    public function getGrandTotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

    /**
     * @param string $uuid
     * @return OrderContract
     */
    public function setUuid(string $uuid): OrderContract
    {
        return $this->setAttribute('uuid', $uuid);
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->getAttribute('uuid');
    }

    /**
     * @return string|null
     */
    public function toPdf(): ?string
    {
        $view = view('pdf.order', [
            'order' => $this
        ]);

        /** @var GeneratorInterface $snappy */
        $snappy = app('snappy.pdf');
        try {
            return $snappy->getOutputFromHtml($view->render());
        } catch (\Throwable $e) {
            \Log::warning($e);
        }

        return null;
    }
}