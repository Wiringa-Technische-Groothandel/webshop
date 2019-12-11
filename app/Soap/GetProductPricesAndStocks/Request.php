<?php

declare(strict_types=1);

namespace WTG\Soap\GetProductPricesAndStocks;

use WTG\Soap\AbstractRequest;

/**
 * GetProductPricesAndStocks request.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPricesAndStocks
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest
{
    /**
     * @var Request\Product[]
     */
    public $products;

    /**
     * @var string
     */
    public $currencyId;

    /**
     * @var string
     */
    public $debtorId;

    /**
     * @var string
     */
    public $stockType = 'Available';

    /**
     * @var bool
     */
    public $isInclPrices = true;

    /**
     * @var bool
     */
    public $isInclPriceAdjustments = true;

    /**
     * @var bool
     */
    public $isInclStocks = true;

    /**
     * @var string
     */
    public $warehouseGroupId = '';

    /**
     * Request constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileId = config('soap.profiles.priceAndStock');
        $this->currencyId = 'EUR';
    }
}
