<?php

namespace WTG\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

/**
 * TrustProxies middleware.
 *
 * @package     WTG\Http
 * @subpackage  Middleware
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
