<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

/**
 * Order item contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface OrderItemContract
{
    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Set the order.
     *
     * @param OrderContract $order
     * @return OrderItemContract
     */
    public function setOrder(OrderContract $order): OrderItemContract;

    /**
     * Get the order.
     *
     * @return null|OrderContract
     */
    public function getOrder(): ?OrderContract;

    /**
     * Set the name.
     *
     * @param string $name
     * @return OrderItemContract
     */
    public function setName(string $name): OrderItemContract;

    /**
     * Get the name.
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set the product sku.
     *
     * @param string $sku
     * @return OrderItemContract
     */
    public function setSku(string $sku): OrderItemContract;

    /**
     * Get the product sku.
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set the item quantity.
     *
     * @param float $quantity
     * @return OrderItemContract
     */
    public function setQuantity(float $quantity): OrderItemContract;

    /**
     * Get the item quantity.
     *
     * @return float
     */
    public function getQuantity(): ?float;

    /**
     * Set the price.
     *
     * @param float|null $price
     * @return OrderItemContract
     */
    public function setPrice(float $price): OrderItemContract;

    /**
     * Get the price.
     *
     * @return null|float
     */
    public function getPrice(): ?float;

    /**
     * Set the subtotal.
     *
     * @param float|null $subtotal
     * @return OrderItemContract
     */
    public function setSubtotal(float $subtotal): OrderItemContract;

    /**
     * Get the subtotal.
     *
     * @return float
     */
    public function getSubtotal(): ?float;
}
