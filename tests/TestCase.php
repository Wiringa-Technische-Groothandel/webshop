<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case.
 *
 * @package Tests
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
