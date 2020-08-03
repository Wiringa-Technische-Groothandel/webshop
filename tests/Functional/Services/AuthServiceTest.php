<?php

declare(strict_types=1);

namespace Tests\Functional\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Tests\Functional\TestCase;
use WTG\Contracts\Services\AuthManagerContract;
use WTG\Models\Customer;

/**
 * Auth service test.
 *
 * @package     Tests\Functional
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class AuthServiceTest extends TestCase
{
    /**
     * @var AuthManagerContract
     */
    protected AuthManagerContract $authService;

    /**
     * @return void
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = $this->app->make(AuthManagerContract::class);
    }

    /**
     * @test
     * @return void
     */
    public function canAuthenticateUserByRequest()
    {
        $request = new Request(
            [
                'company'  => '12345',
                'username' => 'foobar',
                'password' => 'test',
            ]
        );

        $this->assertNotNull($this->authService->authenticateByRequest($request));

        $this->assertAuthenticated();
        $this->assertAuthenticatedAs(Customer::find(1));
    }

    /**
     * @test
     * @return void
     */
    public function returnsNullOnInvalidCompanyCredentials()
    {
        $request = new Request(
            [
                'company'  => 'i dont exist',
                'username' => 'foobar',
                'password' => 'test',
            ]
        );

        $this->assertNull($this->authService->authenticateByRequest($request));

        $this->assertGuest();
    }

    /**
     * @test
     * @return void
     */
    public function returnsNullOnInvalidCustomerCredentials()
    {
        $request = new Request(
            [
                'company'  => '12345',
                'username' => 'i dont exist',
                'password' => 'test',
            ]
        );

        $this->assertNull($this->authService->authenticateByRequest($request));

        $this->assertGuest();
    }

    /**
     * @test
     * @return void
     */
    public function returnsNullOnMissingCredentials()
    {
        $request = new Request(
            [
                'company'  => '12345',
            ]
        );

        $this->assertNull($this->authService->authenticateByRequest($request));

        $this->assertGuest();
    }
}
