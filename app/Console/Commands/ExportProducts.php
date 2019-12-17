<?php

declare(strict_types=1);

namespace WTG\Console\Commands;

use Illuminate\Console\Command;
use Throwable;

/**
 * Trigger full product export.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ExportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger a full product export';

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
        app('soap')->exportProducts();
    }
}
