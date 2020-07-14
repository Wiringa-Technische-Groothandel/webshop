<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Cache\Rebuild;

use Illuminate\Console\Command;
use Throwable;
use WTG\Services\Import\Invoices;

/**
 * Rebuild invoice list cache command.
 */
class InvoiceList extends Command
{
    /**
     * @var string
     */
    protected $name = 'cache:rebuild:invoice-list';

    /**
     * @var string
     */
    protected $description = 'Rebuild the invoice list cache';

    private Invoices $invoiceImporter;

    /**
     * InvoiceList constructor.
     *
     * @param Invoices $invoiceImporter
     */
    public function __construct(Invoices $invoiceImporter)
    {
        parent::__construct();

        $this->invoiceImporter = $invoiceImporter;
    }

    /**
     * Run the command.
     *
     * @return int
     * @throws Throwable
     */
    public function handle()
    {
        try {
            $this->invoiceImporter->rebuildCache();
        } catch (\Throwable $exception) {
            report($exception);

            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        $this->info("The invoice list cache has been rebuilt.");

        return Command::SUCCESS;
    }
}
