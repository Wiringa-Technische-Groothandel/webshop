<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetInvoices\Response;

use Carbon\CarbonImmutable;

/**
 * Invoice response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Invoice
{
    public string $erpId;
    public string $code;
    public string $debtorCode;
    public string $statusCode;
    public string $statusDescription;
    public ?string $description;
    public string $vatCode;
    public string $paymentCondition;

    public CarbonImmutable $date;
    public ?CarbonImmutable $dueDate;

    public float $subtotal;
    public float $vat;
    public float $total;
}
