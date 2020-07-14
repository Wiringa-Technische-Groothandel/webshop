<?php

declare(strict_types=1);

namespace WTG\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Console\ImportCommand;
use WTG\Services\Import\Invoices;

/**
 * Console kernel.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ImportCommand::class, // Added here so it's available from the Artisan facade
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sys:cleanup:companies');

        // Full product import
        $schedule->command('import:products')->dailyAt('4:00');

        // Import product changes
        $schedule->command('import:product-changes')->between('7:00', '20:00')->withoutOverlapping();

        // Process product changes
        $schedule->command('process:staged-product-changes')->everyFiveMinutes()->withoutOverlapping();

        // Re-cache the invoice files
        $schedule->command('cache:rebuild:invoice-list')->hourly()->between('6:00', '22:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
