<?php

namespace WTG\Soap\GetProductPrice;

use WTG\Soap\AbstractRequest;

/**
 * GetProducts request.
 *
 * @package     WTG\Soap
 * @subpackage  GetProducts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest
{
    /**
     * @var string
     */
    public $productId;

    /**
     * @var string
     */
    public $unitId;

    /**
     * @var string
     */
    public $debtorId;

    /**
     * @var float
     */
    public $amount;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileId = config('soap.profiles.priceAndStock');
    }
}