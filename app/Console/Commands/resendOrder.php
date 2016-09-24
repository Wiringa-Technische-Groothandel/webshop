<?php

namespace App\Console\Commands;

use App\Order;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class resendOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'resend:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend a specific order id';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');

        $order = Order::where('id', $orderId)->firstOrFail();

        $data['address'] = $order->getAddress();
        $data['cart'] = unserialize($order->products);
        $data['comment'] = $order->comment.'<br />Verstuurd op: '.$order->created_at;

        \Mail::send('email.resend_order', $data, function ($message) {
            $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

            $message->to('verkoop@wiringa.nl');

            $message->subject('Webshop order [Opnieuw verstuurd!]');
        });
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['order_id', InputArgument::REQUIRED, '(Required) Integer'],
        ];
    }
}
