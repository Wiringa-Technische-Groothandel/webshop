<?php

namespace Tests\Functional;

use WTG\Soap\Service as SoapService;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
     * Preparation stuff.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed');
    }
}