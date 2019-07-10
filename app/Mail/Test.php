<?php

namespace WTG\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Test mailable.
 *
 * @package     WTG\Mail
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Test extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $subject = '[WTG Webshop] - Test';

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.test');
    }
}
