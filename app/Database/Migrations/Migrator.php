<?php

declare(strict_types=1);

namespace WTG\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Migrations\Migrator as LaravelMigrator;
use Illuminate\Support\Str;

/**
 * Database migrator.
 *
 * @package     WTG\Database
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Migrator extends LaravelMigrator
{
    /**
     * @param string $file
     * @return Migration
     */
    public function resolve($file): Migration
    {
        $class = Str::studly(implode('_', array_slice(explode('_', $file), 4)));
        $class = sprintf("\\Database\\Migrations\\%s", $class);

        return new $class();
    }
}
