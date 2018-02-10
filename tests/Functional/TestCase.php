<?php

namespace Tests\Functional;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Functional test case.
 *
 * @package     Tests
 * @subpackage  Functional
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class TestCase extends \Tests\TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

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