<?php

namespace WTG\Soap;

/**
 * Abstract response.
 *
 * @package     WTG
 * @subpackage  Soap
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class AbstractResponse
{
    /**
     * @var string
     */
    public $message = 'Unknown error';

    /**
     * @var int
     */
    public $code = 500;
}