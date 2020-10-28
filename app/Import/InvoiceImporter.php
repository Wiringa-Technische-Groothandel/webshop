<?php

declare(strict_types=1);

namespace WTG\Import;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use WTG\Managers\RestManager;
use WTG\Models\Company;
use WTG\Models\Invoice;
use WTG\RestClient\Model\Parser\InvoiceParser;
use WTG\RestClient\Model\Rest\GetInvoices\Request as GetInvoicesRequest;
use WTG\RestClient\Model\Rest\GetInvoices\Response as GetInvoicesResponse;

/**
 * Invoice importer.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class InvoiceImporter
{
    private RestManager $restManager;
    private int $offset = 0;
    private int $limit = 50;
    private CarbonImmutable $importStartTime;

    /**
     * InvoiceImporter constructor.
     *
     * @param RestManager $restManager
     */
    public function __construct(RestManager $restManager)
    {
        $this->restManager = $restManager;
        $this->importStartTime = CarbonImmutable::now();
    }

    /**
     * Run the importer.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function importInvoices(): void
    {
        foreach ($this->fetchInvoices() as $invoices) {
            $this->processInvoices($invoices);
        }
    }

    /**
     * Run the importer.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function checkUnpaidInvoices(): void
    {
        $invoiceNumbers = Invoice::query()
            ->where('status_code', InvoiceParser::STATUS_CODE_OPEN)
            ->pluck('invoice_number');

        Log::info(
            sprintf(
                '[Invoice import] Checking unpaid invoices for updates: %s',
                $invoiceNumbers->join(',')
            )
        );

        $invoiceNumbers
            ->chunk(10)
            ->each(
                function (Collection $invoiceNumbers) {
                    /** @var GetInvoicesResponse $response */
                    $response = $this->restManager->handle(
                        new GetInvoicesRequest(
                            0,
                            50,
                            sprintf("SalesInvoiceCode IN (%s)", $invoiceNumbers->join(',')),
                            'debtorInfo,paymentConditionInfo'
                        )
                    );

                    $this->processInvoices($response->invoices);
                }
            );
    }

    /**
     * Process fetched invoices.
     *
     * @param Collection $collection
     * @return void
     */
    private function processInvoices(Collection $collection): void
    {
        /** @var GetInvoicesResponse\Invoice $invoice */
        foreach ($collection->all() as $invoice) {
            /** @var Company $company */
            $company = Company::query()
                ->where('customer_number', $invoice->debtorCode)
                ->first();

            if (! $company) {
                Log::notice(
                    sprintf(
                        '[Invoice import] Invoice %s not attached to company, unknown customer number %s',
                        $invoice->code,
                        $invoice->debtorCode
                    )
                );
            }

            $invoice = Invoice::query()->updateOrCreate(
                [
                    'erp_id' => $invoice->erpId
                ],
                [
                    'company_id'         => optional($company)->getId(),
                    'customer_number'    => $invoice->debtorCode,
                    'invoice_number'     => $invoice->code,
                    'invoice_date'       => $invoice->date,
                    'due_date'           => $invoice->dueDate,
                    'description'        => $invoice->description,
                    'subtotal'           => $invoice->subtotal,
                    'vat'                => $invoice->vat,
                    'total'              => $invoice->total,
                    'vat_code'           => $invoice->vatCode,
                    'status_code'        => $invoice->statusCode,
                    'status_description' => $invoice->statusDescription,
                ]
            );

            Log::info(sprintf('[Invoice import] Created/updated invoice %s', $invoice->id));
        }
    }

    /**
     * Fetch invoices from the ERP.
     *
     * @return iterable
     * @throws BindingResolutionException
     */
    private function fetchInvoices(): iterable
    {
        $lastImportTime = DB::table('config')
            ->where('key', 'last_invoice_import')
            ->value('value');

        if (! $lastImportTime) {
            $lastImportTime = CarbonImmutable::createFromDate(2019, 1, 1);
        } else {
            $lastImportTime = CarbonImmutable::parse($lastImportTime)->subMonth();
        }

        Log::debug(
            sprintf(
                '[Invoice import] Importing invoices from %s',
                $lastImportTime->format('Y-m-d')
            )
        );

        while (true) {
            /** @var GetInvoicesResponse $response */
            $response = $this->restManager->handle(
                new GetInvoicesRequest(
                    $this->offset,
                    $this->limit,
                    sprintf('InvoiceDate GT %s', $lastImportTime->format('Y-m-d')),
                    'debtorInfo,paymentConditionInfo'
                )
            );

            if ($response->invoices->isEmpty()) {
                break;
            }

            yield $response->invoices;

            $this->offset = ($this->offset + $this->limit);
        }

        DB::table('config')
            ->updateOrInsert(
                ['key' => 'last_invoice_import'],
                ['value' => $this->importStartTime]
            );
    }
}
