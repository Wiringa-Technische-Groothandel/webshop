<?php

declare(strict_types=1);

namespace WTG\Soap;

/**
 * Security context
 *
 * @package     WTG
 * @subpackage  Soap
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SecurityContext
{
    /**
     * @var string
     */
    public $sessionToken;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $password;

    /**
     * SecurityContext constructor.
     *
     * @param  string  $user
     * @param  string  $password
     * @param  string  $sessionToken
     */
    public function __construct(string $user, string $password, string $sessionToken = "")
    {
        $this->userId = $user;
        $this->password = $password;
        $this->sessionToken = $sessionToken;
    }
}