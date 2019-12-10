<?php

declare(strict_types=1);

namespace WTG\Soap\GetOrderCount;

use WTG\Soap\AbstractResponse;

/**
 * GetOrderCount response.
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