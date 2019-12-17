<?php

declare(strict_types=1);

namespace WTG\Console;

use Cache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
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

        $schedule->command('import:products')->dailyAt('4:00');

        // Re-cache the invoice files
        $schedule->call(
            function () {
                Cache::forget('invoice_files');

                /** @var Invoices $service */
                $service = app()->make(Invoices::class);
                $service->getFileList(true);
            }
        )->dailyAt('21:30');
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
