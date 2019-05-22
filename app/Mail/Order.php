<?php

namespace WTG\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\OrderContract;

/**
 * Order mailable.
 *
 * @package     WTG\Mail
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Order extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var OrderContract
     */
    public $order;

    /**
     * @var CustomerContract
     */
    public $customer;

    /**
     * Create a new message instance.
     *
     * @param  OrderContract  $order
     * @param  CustomerContract  $customer
     */
    public function __construct(OrderContract $order, CustomerContract $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->customer->getContact()->getOrderEmail()) {
            $this->to(
                $this->customer->getContact()->getOrderEmail(),
                $this->customer->getCompany()->getName()
            );
        }

        return $this
            ->subject(__('[WTG Webshop] - Bestelbevestiging'))
            ->bcc([
                ['name' => 'Wiringa Technische Groothandel', 'email' => config('wtg.orderReceiveEmail')]
            ])
            ->markdown('emails.order');
    }
}
