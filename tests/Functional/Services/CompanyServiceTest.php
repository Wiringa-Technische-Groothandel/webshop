<?php

namespace Tests\Functional\Services;

use Tests\Functional\TestCase;
use WTG\Services\CompanyService;
use WTG\Contracts\Services\CompanyServiceContract;

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
        $company = $service->createCompany([
            'name' => 'Test company',
            'street' => 'Foobar 1',
            'city' => 'Wefwef',
            'postcode' => '1234 XX',
            'customer-number' => '54321'
        ]);

        $this->assertNotEmpty($company->getId());
    }

    /**
     * @test
     */
    public function createsDefaultCustomerForNewCompany()
    {
        /** @var CompanyService $service */
        $service = app()->make(CompanyServiceContract::class);
        $company = $service->createCompany([
            'name' => 'Test company',
            'street' => 'Foobar 1',
            'city' => 'Wefwef',
            'postcode' => '1234 XX',
            'customer-number' => '54321'
        ]);

        $this->assertEquals(1, $company->getCustomers()->count());
    }

    /**
     * @test
     * @expectedException \WTG\Exceptions\Company\DuplicateCustomerNumberException
     */
    public function throwsExceptionOnDuplicateCustomerNumber()
    {
        /** @var CompanyService $service */
        $service = app()->make(CompanyServiceContract::class);
        $service->createCompany([
            'name' => 'Test company',
            'street' => 'Foobar 1',
            'city' => 'Wefwef',
            'postcode' => '1234 XX',
            'customer-number' => '12345' // Created in seeder
        ]);
    }

    /**
     * @test
     * @expectedException \WTG\Exceptions\Company\IncompleteDataException
     */
    public function throwsExceptionOnInvalidOrIncompleteData()
    {
        /** @var CompanyService $service */
        $service = app()->make(CompanyServiceContract::class);
        $service->createCompany([]);
    }
}