<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Cache\Rebuild;

use Illuminate\Console\Command;
use Throwable;
use WTG\Managers\InvoiceManager;

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

    private InvoiceManager $invoiceManager;

    /**
     * InvoiceList constructor.
     *
     * @param InvoiceManager $invoiceManager
     */
    public function __construct(InvoiceManager $invoiceManager)
    {
        parent::__construct();

        $this->invoiceManager = $invoiceManager;
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
            $this->invoiceManager->rebuildCache();
        } catch (\Throwable $exception) {
            report($exception);

            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        $this->info("The invoice list cache has been rebuilt.");

        return Command::SUCCESS;
    }
}
