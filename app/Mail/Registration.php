<?php

declare(strict_types=1);

namespace WTG\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use WTG\Models\Registration as RegistrationModel;

/**
 * Registration mailable.
 *
 * @package     WTG\Mail
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Registration extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var RegistrationModel
     */
    public $registration;

    /**
     * @var string
     */
    public $subject = '[WTG Webshop] - Registratie verzoek';

    /**
     * Create a new message instance.
     *
     * @param RegistrationModel $registration
     * @return void
     */
    public function __construct(RegistrationModel $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.registration');
    }
}
