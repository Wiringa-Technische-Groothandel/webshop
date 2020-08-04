<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetOrders\Response;

use Carbon\CarbonImmutable;

class Order
{
    public string $erpId;
    public string $orderNumber;
    public string $debtorCode;
    public float $discountPercentage;
    public float $netAmount;
    public float $netAmountIncludingVat;
    public string $orderStatus;
    public string $orderStatusDescription;
    public string $orderReasonBlocked;
    public CarbonImmutable $orderDate;
}
