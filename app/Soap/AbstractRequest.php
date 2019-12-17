<?php

declare(strict_types=1);

namespace WTG\Soap;

use Carbon\Carbon;

/**
 * Abstract soap request.
 *
 * @package     WTG
 * @subpackage  Soap
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class AbstractRequest
{
    /**
     * @var
     */
    public $securityContext;

    /**
     * @var string
     */
    public $adminId;

    /**
     * @var string
     */
    public $profileId;

    /**
     * @var string
     */
    public $contextDate;

    /**
     * AbstractRequest constructor.
     */
    public function __construct()
    {
        $this->securityContext = new SecurityContext(config('soap.user'), config('soap.pass'));
        $this->adminId = config('soap.admin');
        $this->contextDate = Carbon::now()->format('Y-m-d');
    }
}
