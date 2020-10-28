<?php

declare(strict_types=1);

namespace WTG\GraphQL\Fields;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use WTG\DTO\InvoiceFile;
use WTG\Managers\InvoiceManager;
use WTG\Models\Company;
use WTG\Models\Invoice as InvoiceModel;

/**
 * Invoice field resolver.
 *
 * @package     WTG\GraphQL\Fields
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Invoice
{
    protected InvoiceManager $invoiceManager;

    /**
     * Invoice constructor.
     *
     * @param InvoiceManager $invoiceManager
     */
    public function __construct(InvoiceManager $invoiceManager)
    {
        $this->invoiceManager = $invoiceManager;
    }

    public function resolveIsDownloadable(InvoiceModel $invoice, array $args, GraphQLContext $context): ?bool
    {
        /** @var Company $company */
        $company = optional($context->user())->getCompany();
        if (! $company) {
            return null;
        }

        return (bool) $this->getInvoiceFile($invoice, $company);
    }

    /**
     * Resolve the 'fileData' field for graphql.
     *
     * @param InvoiceModel $invoice
     * @param array $args
     * @param GraphQLContext $context
     * @return string|null
     */
    public function resolveFileData(InvoiceModel $invoice, array $args, GraphQLContext $context): ?string
    {
        /** @var Company $company */
        $company = optional($context->user())->getCompany();
        if (! $company) {
            return null;
        }

        $invoiceFile = $this->getInvoiceFile($invoice, $company);
        if (! $invoiceFile) {
            return null;
        }

        try {
            return base64_encode($this->invoiceManager->readFile($invoiceFile->getFilename()));
        } catch (FileNotFoundException $e) {
            Log::warning(
                'Invoice file found in cache but not on the filesystem. (File removed from FTP?)',
                [
                    'filename' => $invoiceFile->getFilename()
                ]
            );

            return null;
        }
    }

    /**
     * Get the invoice file for the provided invoice model.
     *
     * @param InvoiceModel $invoice
     * @param Company $company
     * @return InvoiceFile|null
     */
    private function getInvoiceFile(InvoiceModel $invoice, Company $company): ?InvoiceFile
    {
        $invoiceFiles = $this->invoiceManager->getForCompany($company);

        return $invoiceFiles->first(
            function (InvoiceFile $invoiceFile) use ($invoice) {
                return $invoiceFile->getInvoiceNumber() === $invoice->invoice_number;
            }
        );
    }
}
