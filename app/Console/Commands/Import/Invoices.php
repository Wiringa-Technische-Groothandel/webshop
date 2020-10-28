<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Throwable;
use WTG\Import\InvoiceImporter;

/**
 * Import invoices command.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Invoices extends Command
{
    /**
     * @var string
     */
    protected $name = 'import:invoices';

    /**
     * @var string
     */
    protected $description = 'Import invoice data from the ERP.';

    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * @var InvoiceImporter
     */
    protected InvoiceImporter $invoiceImporter;

    /**
     * Invoices constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param InvoiceImporter $invoiceImporter
     */
    public function __construct(DatabaseManager $databaseManager, InvoiceImporter $invoiceImporter)
    {
        parent::__construct();

        $this->databaseManager = $databaseManager;
        $this->invoiceImporter = $invoiceImporter;
    }

    /**
     * Run the command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        $this->databaseManager->transaction(
            function () {
                $this->invoiceImporter->importInvoices();
            }
        );
    }
}
