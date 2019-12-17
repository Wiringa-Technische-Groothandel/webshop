<?php

declare(strict_types=1);

namespace WTG\Soap\GetOrderCount;

use WTG\Soap\AbstractRequest;

/**
 * GetOrderCount request.
 *
 * @package     WTG\Soap
 * @subpackage  GetOrderCount
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest
{
    /**
     * @var string
     */
    public $customerId;

    /**
     * @var string
     */
    public $orderDateFrom;

    /**
     * @var string
     */
    public $orderDateTo;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileId = config('soap.profiles.priceAndStock');
    }
}
