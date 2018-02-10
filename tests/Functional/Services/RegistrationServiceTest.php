<?php

namespace Tests\Functional\Services;

use Tests\Functional\TestCase;
use WTG\Contracts\Services\RegistrationServiceContract;

/**
 * Registration service test.
 *
 * @package     Tests
 * @subpackage  Functional\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RegistrationServiceTest extends TestCase
{
    /**
     * @var RegistrationServiceContract
     */
    protected $service;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->service = $this->app->make(RegistrationServiceContract::class);
    }

    /**
     * @test
     * @return void
     */
    public function createsRegistrationFromArray()
    {
        $data = [
            'contact-company'       => 'Test company',
            'contact-name'          => 'Test Name',
            'contact-address'       => 'Street 4321',
            'contact-city'          => 'City',
            'contact-postcode'      => '1234AB',
            'contact-phone-company' => '1234567890',
            'contact-phone'         => '0987654321',
            'contact-email'         => 'foo@bar.com',
            'contact-website'       => 'https://foobar.com',

            'business-address'      => 'Street 1234',
            'business-city'         => 'City',
            'business-postcode'     => '9876XX',
            'business-phone'        => '1234568790',

            'payment-iban'          => '12341234',
            'payment-kvk'           => '98769876',
            'payment-vat'           => '12345687',

            'other-alt-email'           => 'bar@foo.com',
            'other-order-confirmation'  => true,
            'other-mail-productfile'    => false
        ];

        $registration = $this->service->create($data);

        $this->assertArraySubset($data, $registration->toArray());
    }
}