<?php

namespace WTG\Soap\GetProducts;

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
    public $productId = "";

    /**
     * @var string
     */
    public $unitId = "";

    /**
     * @var int
     */
    public $maxQuantity;

    /**
     * @var int
     */
    public $indexFrom = 0;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileId = config('soap.profiles.product');
    }
}