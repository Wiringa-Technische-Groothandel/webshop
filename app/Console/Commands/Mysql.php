<?php

declare(strict_types=1);

namespace WTG\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
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
     * @var string
     */
    protected $name = 'mysql';

    /**
     * @var string
     */
    protected $description = 'Open a mysql cli session';

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

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                "mycli",
                null,
                InputOption::VALUE_NONE,
                "Use mycli instead of mysql"
            ]
        ];
    }
}
