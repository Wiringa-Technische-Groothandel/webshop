<?php

namespace WTG\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \WTG\Models\Registration
     */
    public $registration;

    /**
     * Create a new message instance.
     *
     * @param  \WTG\Models\Registration  $registration
     * @return void
     */
    public function __construct(\WTG\Models\Registration $registration)
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
        return $this
            ->markdown('emails.registration');
    }
}
