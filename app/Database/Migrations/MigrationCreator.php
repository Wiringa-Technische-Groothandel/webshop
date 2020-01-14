<?php

declare(strict_types=1);

namespace WTG\Database\Migrations;

use Illuminate\Database\Migrations\MigrationCreator as LaravelMigrationCreator;

/**
 * Database migration creator.
 *
 * @package     WTG\Database
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class MigrationCreator extends LaravelMigrationCreator
{
    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function stubPath()
    {
        return __DIR__ . '/stubs';
    }
}
