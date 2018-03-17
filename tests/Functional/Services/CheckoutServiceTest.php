<?php

namespace Tests\Functional\Services;

use WTG\Mail\Order;
use WTG\Models\Address;
use WTG\Models\Customer;
use WTG\Models\QuoteItem;
use Tests\Functional\TestCase;
use WTG\Services\CheckoutService;
use WTG\Contracts\Services\CartServiceContract;

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
    public function setUp()
    {
        parent::setUp();

        $this->actingAs(Customer::find(1));
    }

    /**
     * @test
     * @expectedException \WTG\Exceptions\Checkout\EmptyCartException
     */
    public function throwsExceptionOnEmptyCart()
    {
        $cartMock = $this->createMock(CartServiceContract::class);
        $cartMock->method('getItems')->willReturn(collect([]));
        $cartMock->method('getDeliveryAddress')->willReturn(Address::find(1));

        app()->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = app()->make(CheckoutService::class);
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

        app()->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = app()->make(CheckoutService::class);
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

        app()->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = app()->make(CheckoutService::class);
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

        app()->instance(CartServiceContract::class, $cartMock);

        /** @var CheckoutService $checkoutService */
        $checkoutService = app()->make(CheckoutService::class);
        $checkoutService->createOrder();

        $this->mailFake->assertSent(Order::class);
    }
}