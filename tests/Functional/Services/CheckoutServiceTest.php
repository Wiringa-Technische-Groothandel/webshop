<?php

namespace Tests\Functional\Services;

use Tests\Functional\TestCase;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Exceptions\Checkout\EmptyCartException;
use WTG\Mail\Order;
use WTG\Models\Address;
use WTG\Models\Customer;
use WTG\Models\QuoteItem;
use WTG\Services\CheckoutService;

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

        $cartMock = $this->createMock(CartServiceContract::class);
        $cartMock->method('getItems')->willReturn(collect([]));
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = $this->app->make(CheckoutService::class);
        $checkoutService->createOrder("Test comment");
    }

    /**
     * @test
     */
    public function createsOrderWithComment(): void
    {
        $cartMock = $this->createMock(CartServiceContract::class);
        $cartMock->method('getItems')->willReturn(QuoteItem::all());
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = $this->app->make(CheckoutService::class);
        $order = $checkoutService->createOrder("Test comment");

        $this->assertEquals("Test comment", $order->getComment());
    }

    /**
     * @test
     */
    public function createsOrderWithoutComment(): void
    {
        $cartMock = $this->createMock(CartServiceContract::class);
        $cartMock->method('getItems')->willReturn(QuoteItem::all());
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = $this->app->make(CheckoutService::class);
        $order = $checkoutService->createOrder();

        $this->assertNull($order->getComment());
    }

    /**
     * @test
     */
    public function sendsOrderConfirmationMail(): void
    {
        $cartMock = $this->createMock(CartServiceContract::class);
        $cartMock->method('getItems')->willReturn(QuoteItem::all());
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        $this->app->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = $this->app->make(CheckoutService::class);
        $checkoutService->createOrder();

        $this->mailFake->assertSent(Order::class);
    }
}
