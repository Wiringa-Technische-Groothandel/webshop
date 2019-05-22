<?php

namespace WTG\Services;

use WTG\Mail\Registration as RegistrationMail;
use WTG\Models\Registration;
use WTG\Mail\RegistrationSuccess;
use Illuminate\Contracts\Mail\Mailer;
use WTG\Contracts\Models\RegistrationContract;
use WTG\Contracts\Services\RegistrationServiceContract;

/**
 * Registration service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RegistrationService implements RegistrationServiceContract
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * RegistrationService constructor.
     *
     * @param  Mailer  $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Create a registration from request.
     *
     * @param  array  $data
     * @return RegistrationContract
     */
    public function create(array $data): RegistrationContract
    {
        $data = array_map(function ($value) {
            if (strtolower($value) === 'on') {
                return 1;
            }

            return $value;
        }, $data);

        /** @var Registration $registration */
        $registration = app()->make(RegistrationContract::class);
        $registration->fill($data);
        $registration->save();

        $this->sendRegistrationSuccessMail($data['contact-email']);
        $this->sendRegistrationMail($registration);

        return $registration;
    }

    /**
     * Send a success registration email.
     *
     * @param  string  $recipientEmail
     * @return void
     */
    protected function sendRegistrationSuccessMail(string $recipientEmail): void
    {
        $this->mailer->to($recipientEmail)->send(new RegistrationSuccess);
    }

    /**
     * Send a registration email.
     *
     * @param  Registration  $registration
     * @return void
     */
    protected function sendRegistrationMail(Registration $registration): void
    {
        $this->mailer->to(config('wtg.orderReceiveEmail'))->send(new RegistrationMail($registration));
    }
}