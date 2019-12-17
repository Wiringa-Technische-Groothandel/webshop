<?php

declare(strict_types=1);

namespace WTG\Soap\ExportProducts;

use WTG\Soap\AbstractRequest;

/**
 * ExportProducts request.
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
    public $exportType = 'M';

    /**
     * @var bool
     */
    public $splitFile = false;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->profileId = config('soap.profiles.product');
    }
}
