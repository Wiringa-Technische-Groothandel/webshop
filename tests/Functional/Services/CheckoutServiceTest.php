<?php

namespace Tests\Functional\Services;

use Tests\Functional\TestCase;
use WTG\Contracts\Services\CartManagerContract;
use WTG\Exceptions\Checkout\EmptyCartException;
use WTG\Mail\Order;
use WTG\Managers\CheckoutManager;
use WTG\Models\Address;
use WTG\Models\Customer;
use WTG\Models\QuoteItem;

/**
 * Unit test case.
 *
 * @package     Tests
 * @subpackage  Unit\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CheckoutServiceTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Customer::find(1));
    }

    /**
     * @test
     */
    public function throwsExceptionOnEmptyCart()
    {
        $this->expectException(EmptyCartException::class);

        $cartMock = $this->createMock(CartManagerContract::class);
        $cartMock->method('getItems')->willReturn(collect([]));
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartManagerContract::class, $cartMock);

        /** @var CheckoutManager $checkoutService */
        $checkoutService = $this->app->make(CheckoutManager::class);
        $checkoutService->createOrder("Test comment");
    }

    /**
     * @test
     */
    public function createsOrderWithComment(): void
    {
        $cartMock = $this->createMock(CartManagerContract::class);
        $cartMock->method('getItems')->willReturn(QuoteItem::all());
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartManagerContract::class, $cartMock);

        /** @var CheckoutManager $checkoutService */
        $checkoutService = $this->app->make(CheckoutManager::class);
        $order = $checkoutService->createOrder("Test comment");

        $this->assertEquals("Test comment", $order->getComment());
    }

    /**
     * @test
     */
    public function createsOrderWithoutComment(): void
    {
        $cartMock = $this->createMock(CartManagerContract::class);
        $cartMock->method('getItems')->willReturn(QuoteItem::all());
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartManagerContract::class, $cartMock);

        /** @var CheckoutManager $checkoutService */
        $checkoutService = $this->app->make(CheckoutManager::class);
        $order = $checkoutService->createOrder();

        $this->assertNull($order->getComment());
    }

    /**
     * @test
     */
    public function sendsOrderConfirmationMail(): void
    {
        $cartMock = $this->createMock(CartManagerContract::class);
        $cartMock->method('getItems')->willReturn(QuoteItem::all());
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartManagerContract::class, $cartMock);

        /** @var CheckoutManager $checkoutService */
        $checkoutService = $this->app->make(CheckoutManager::class);
        $checkoutService->createOrder();

        $this->mailFake->assertSent(Order::class);
    }
}
