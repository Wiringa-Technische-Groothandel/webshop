<?php

declare(strict_types=1);

namespace WTG\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WTG\Catalog\Model\Product;
use WTG\Catalog\ProductManager;
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
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * OrderItem constructor.
     *
     * @param array          $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->productManager = app(ProductManager::class);
    }

    /**
     * Set the order.
     *
     * @param OrderContract $order
     * @return OrderItemContract
     */
    public function setOrder(OrderContract $order): OrderItemContract
    {
        $this->order()->associate($order);

        return $this;
    }

    /**
     * Order relation.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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
     * @param string $name
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
     * @param string $sku
     * @return OrderItemContract
     */
    public function setSku(string $sku): OrderItemContract
    {
        return $this->setAttribute('sku', $sku);
    }

    /**
     * Set the item quantity.
     *
     * @param float|null $quantity
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
        return (float)$this->getAttribute('qty');
    }

    /**
     * Set the price.
     *
     * @param float $price
     * @return OrderItemContract
     */
    public function setPrice(float $price): OrderItemContract
    {
        return $this->setAttribute('price', $price);
    }

    /**
     * Get the price.
     *
     * @return null|float
     */
    public function getPrice(): ?float
    {
        return (float)$this->getAttribute('price');
    }

    /**
     * Set the subtotal.
     *
     * @param float $subtotal
     * @return OrderItemContract
     */
    public function setSubtotal(float $subtotal): OrderItemContract
    {
        return $this->setAttribute('subtotal', $subtotal);
    }

    /**
     * Get the subtotal.
     *
     * @return float
     */
    public function getSubtotal(): ?float
    {
        return (float)$this->getAttribute('subtotal');
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        try {
            return $this->productManager->find($this->getSku());
        } catch (Exception $exception) {
            return null;
        }
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
}
