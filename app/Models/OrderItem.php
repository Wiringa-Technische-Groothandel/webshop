<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\OrderContract;
use WTG\Contracts\Models\OrderItemContract;

/**
 * Order item model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OrderItem extends Model implements OrderItemContract
{
    /**
     * Order relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Set the order.
     *
     * @param  OrderContract  $order
     * @return OrderItemContract
     */
    public function setOrder(OrderContract $order): OrderItemContract
    {
        $this->order()->associate($order);

        return $this;
    }

    /**
     * Get the order.
     *
     * @return null|OrderContract
     */
    public function getOrder(): ?OrderContract
    {
        return $this->getAttribute('order');
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
     * Set the name.
     *
     * @param  string  $name
     * @return OrderItemContract
     */
    public function setName(string $name): OrderItemContract
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
     * Set the sku.
     *
     * @param  string  $sku
     * @return OrderItemContract
     */
    public function setSku(string $sku): OrderItemContract
    {
        return $this->setAttribute('sku', $sku);
    }

    /**
     * Get the sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->getAttribute('sku');
    }

    /**
     * Set the item quantity.
     *
     * @param  float|null  $quantity
     * @return OrderItemContract
     */
    public function setQuantity(float $quantity): OrderItemContract
    {
        return $this->setAttribute('qty', $quantity);
    }

    /**
     * Get the item quantity.
     *
     * @return float
     */
    public function getQuantity(): ?float
    {
        return $this->getAttribute('qty');
    }

    /**
     * Set the price.
     *
     * @param  string  $price
     * @return OrderItemContract
     */
    public function setPrice(string $price): OrderItemContract
    {
        return $this->setAttribute('price', $price);
    }

    /**
     * Get the price.
     *
     * @return null|string
     */
    public function getPrice(): ?string
    {
        return $this->getAttribute('price');
    }

    /**
     * Set the subtotal.
     *
     * @param  string  $subtotal
     * @return OrderItemContract
     */
    public function setSubtotal(string $subtotal): OrderItemContract
    {
        return $this->setAttribute('subtotal', $subtotal);
    }

    /**
     * Get the subtotal.
     *
     * @return string
     */
    public function getSubtotal(): ?string
    {
        return $this->getAttribute('subtotal');
    }
}