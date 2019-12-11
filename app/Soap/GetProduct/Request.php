<?php

declare(strict_types=1);

namespace WTG\Soap\GetProduct;

use WTG\Soap\AbstractRequest;

/**
 * GetProduct request.
 *
 * @package     WTG\Soap
 * @subpackage  GetProduct
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
    public $maxQuantity = 1;

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