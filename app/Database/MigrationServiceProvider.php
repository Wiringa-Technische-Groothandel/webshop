<?php

declare(strict_types=1);

namespace WTG\Database;

use WTG\Database\Migrations\MigrationCreator;
use WTG\Database\Migrations\Migrator;

/**
 * Database migrations service provider.
 *
 * @package     WTG\Database
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class MigrationServiceProvider extends \Illuminate\Database\MigrationServiceProvider
{
    /**
     * Register the migrator service.
     *
     * @return void
     */
    public function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            return new Migrator($repository, $app['db'], $app['files'], $app['events']);
        });
    }

    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('migration.creator', function ($app) {
            return new MigrationCreator($app['files']);
        });
    }
}
