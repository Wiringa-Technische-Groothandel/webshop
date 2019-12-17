<?php

namespace Tests\Functional\Services;

use Tests\Functional\TestCase;
use WTG\Contracts\Services\CompanyServiceContract;
use WTG\Exceptions\Company\DuplicateCustomerNumberException;
use WTG\Exceptions\Company\IncompleteDataException;
use WTG\Services\CompanyService;

/**
 * Company service test.
 *
 * @package     Tests
 * @subpackage  Functional\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CompanyServiceTest extends TestCase
{
    /**
     * @test
     */
    public function createsCompany()
    {
        /** @var CompanyService $service */
        $service = app()->make(CompanyServiceContract::class);
        $company = $service->createCompany(
            [
                'name'            => 'Test company',
                'email'           => 'foo@bar.com',
                'street'          => 'Foobar 1',
                'city'            => 'Wefwef',
                'postcode'        => '1234 XX',
                'customer_number' => '54321',
            ]
        );

        $this->assertNotEmpty($company->getId());
    }

    /**
     * @test
     */
    public function createsDefaultCustomerForNewCompany()
    {
        /** @var CompanyService $service */
        $service = app()->make(CompanyServiceContract::class);
        $company = $service->createCompany(
            [
                'name'            => 'Test company',
                'email'           => 'foo@bar.com',
                'street'          => 'Foobar 1',
                'city'            => 'Wefwef',
                'postcode'        => '1234 XX',
                'customer_number' => '54321',
            ]
        );

        $this->assertEquals(1, $company->getCustomers()->count());
    }

    /**
     * @test
     */
    public function throwsExceptionOnDuplicateCustomerNumber()
    {
        $this->expectException(DuplicateCustomerNumberException::class);

        /** @var CompanyService $service */
        $service = app()->make(CompanyServiceContract::class);
        $service->createCompany(
            [
                'name'            => 'Test company',
                'email'           => 'foo@bar.com',
                'street'          => 'Foobar 1',
                'city'            => 'Wefwef',
                'postcode'        => '1234 XX',
                'customer_number' => '12345' // Created in seeder
            ]
        );
    }

    /**
     * @test
     */
    public function throwsExceptionOnInvalidOrIncompleteData()
    {
        $this->expectException(IncompleteDataException::class);

        /** @var CompanyService $service */
        $service = app()->make(CompanyServiceContract::class);
        $service->createCompany([]);
    }
}
