<?php

declare(strict_types=1);

namespace WTG\Console\Commands;

use Illuminate\Console\Command;
use Throwable;

/**
 * Open a mysql console.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Mysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql {--mycli : Use mycli instead of mysql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open a mysql cli session';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $pipes = [];
        $command = sprintf(
            '%s -h%s -u%s -p%s -P%s %s',
            ($this->option('mycli') ? 'mycli' : 'mysql'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.port'),
            config('database.connections.mysql.database')
        );

        $process = proc_open(
            $command,
            [
                STDIN,
                STDOUT,
                STDERR,
            ],
            $pipes
        );

        if (is_resource($process)) {
            proc_close($process);
        }
    }
}
