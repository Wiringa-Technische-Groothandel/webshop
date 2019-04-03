<?php declare(strict_types=1);

namespace WTG\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class RegistrationSuccess extends Mailable
{
    use Queueable;

    /**
     * @var string
     */
    public $subject = 'Registratie verzoek verstuurd';

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.registration-success');
    }
}