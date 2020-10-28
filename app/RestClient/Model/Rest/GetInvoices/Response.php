<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetInvoices;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Parser\InvoiceParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetInvoices response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public Collection $invoices;

    protected InvoiceParser $invoiceParser;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param LogManager $logManager
     * @param InvoiceParser $invoiceParser
     */
    public function __construct(
        array $responseData,
        LogManager $logManager,
        InvoiceParser $invoiceParser
    ) {
        $this->invoiceParser = $invoiceParser;

        parent::__construct($responseData, $logManager);
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $invoices = collect();

        foreach ($this->responseData ?? [] as $invoice) {
            $invoices->push(
                $this->invoiceParser->parse($invoice)
            );
        }

        $this->invoices = $invoices;
    }
}
