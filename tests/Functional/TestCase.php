<?php

namespace Tests\Functional;

use Illuminate\Support\Testing\Fakes\MailFake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Mail\Mailer as MailerContract;

/**
 * Functional test case.
 *
 * @package     Tests
 * @subpackage  Functional
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class TestCase extends \Tests\TestCase
{
    use RefreshDatabase;

    /**
     * @var MailFake
     */
    protected $mailFake;

    /**
     * Preparation stuff.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

        $this->mailFake = new MailFake;
        $this->app->instance(MailerContract::class, $this->mailFake);
    }
}