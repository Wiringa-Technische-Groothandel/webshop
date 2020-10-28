<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Parser;

use Carbon\CarbonImmutable;
use WTG\RestClient\Model\Rest\GetInvoices\Response\Invoice;

/**
 * Invoice parser.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class InvoiceParser
{
    public const STATUS_CODE_OPEN = 'G';
    public const STATUS_CODE_PAID = 'Z';

    private const DATE_FORMAT = 'Y-m-d';
    private const STATUS_OPEN = 'Openstaand';
    private const STATUS_PAID = 'Betaald';

    /**
     * Parse a response product.
     *
     * @param array $item
     * @return Invoice
     */
    public function parse(array $item): Invoice
    {
        $dueDate = $item['dueDate'] ? CarbonImmutable::createFromFormat(self::DATE_FORMAT, $item['dueDate']) : null;

        $invoice = new Invoice();
        $invoice->erpId = $item['id'];
        $invoice->debtorCode = $item['debtorCode'];
        $invoice->code = $item['salesInvoiceCode'];
        $invoice->vatCode = $item['orderCostsVatGroup'];
        $invoice->date = CarbonImmutable::createFromFormat(self::DATE_FORMAT, $item['invoiceDate']);
        $invoice->dueDate = $dueDate;
        $invoice->statusCode = $item['financialStatusCode'];
        $invoice->statusDescription = $this->parseStatusCode($item['financialStatusCode']);
        $invoice->subtotal = $item['amountGoods'];
        $invoice->vat = $item['amountVat'];
        $invoice->total = $item['amountInvoice'];
        $invoice->description = $item['description'] ?: null;
        $invoice->paymentCondition = $item['paymentConditionInfo']['description'];

        return $invoice;
    }

    private function parseStatusCode(string $financialStatusCode)
    {
        switch ($financialStatusCode) {
            case self::STATUS_CODE_OPEN:
                return self::STATUS_OPEN;
            case self::STATUS_CODE_PAID:
            default:
                return self::STATUS_PAID;
        }
    }
}
