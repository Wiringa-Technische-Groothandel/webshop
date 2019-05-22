<?php

namespace WTG\Soap\ExportProducts;

use WTG\Soap\AbstractResponse;

/**
 * ExportProducts response.
 *
 * @package     WTG\Soap
 * @subpackage  GetOrderCount
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    /**
     * @var int
     */
    public $count;
}