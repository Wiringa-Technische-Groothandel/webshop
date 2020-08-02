<?php

declare(strict_types=1);

namespace WTG\Managers;

use Exception;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Database\Connection as DbConnection;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\OrderContract;
use WTG\Contracts\Models\OrderItemContract;
use WTG\Contracts\Services\AuthManagerContract;
use WTG\Contracts\Services\CartManagerContract;
use WTG\Contracts\Services\CheckoutManagerContract;
use WTG\Exceptions\Checkout\EmptyCartException;
use WTG\Models\Order;
use WTG\Models\OrderItem;

/**
 * Checkout manager.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CheckoutManager implements CheckoutManagerContract
{
    protected $authService;
    protected $cartService;
    protected $mailer;
    protected $db;

    /**
     * CartService constructor.
     *
     * @param CartManagerContract $cartService
     * @param AuthManagerContract $authService
     * @param MailerContract $mailer
     * @param DbConnection $db
     */
    public function __construct(
        CartManagerContract $cartService,
        AuthManagerContract $authService,
        MailerContract $mailer,
        DbConnection $db
    ) {
        $this->cartService = $cartService;
        $this->authService = $authService;
        $this->mailer = $mailer;
        $this->db = $db;
    }

    /**
     * Create an order.
     *
     * @param null|string $comment
     * @return OrderContract
     * @throws EmptyCartException
     * @throws Exception
     */
    public function createOrder(?string $comment = null): OrderContract
    {
        $customer = $this->authService->getCurrentCustomer();
        $address = $this->cartService->getDeliveryAddress();
        $items = $this->cartService->getItems(true);

        if (count($items) < 1) {
            throw new EmptyCartException();
        }

        $this->db->beginTransaction();

        try {
            /** @var Order $order */
            $order = app()->make(OrderContract::class);

            $order->setCompany($customer->getCompany());
            $order->setCustomerNumber($customer->getCompany()->getCustomerNumber());
            $order->setName($address->getName());
            $order->setStreet($address->getStreet());
            $order->setPostcode($address->getPostcode());
            $order->setCity($address->getCity());
            $order->setComment($comment);

            $order->save();

            foreach ($items as $item) {
                /** @var CartItemContract $item */
                $orderItem = $this->createOrderItem($item);

                $order->items()->save($orderItem);
            }

            $this->sendNewOrderMail($order, $customer);

            $this->cartService->markFinished();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        return $order;
    }

    /**
     * Turn a quote item into an order item.
     *
     * @param CartItemContract $item
     * @return OrderItem
     */
    public function createOrderItem(CartItemContract $item): OrderItemContract
    {
        $orderItem = app()->make(OrderItemContract::class);

        $orderItem->setName($item->getProduct()->getName());
        $orderItem->setSku($item->getProduct()->getSku());
        $orderItem->setQuantity($item->getQuantity());
        $orderItem->setPrice($item->getPrice());
        $orderItem->setSubtotal($item->getSubtotal());

        return $orderItem;
    }

    /**
     * Send a new order email.
     *
     * @param OrderContract $order
     * @param CustomerContract $customer
     * @return void
     */
    protected function sendNewOrderMail(OrderContract $order, CustomerContract $customer): void
    {
        $this->mailer->send(new \WTG\Mail\Order($order, $customer));
    }
}
