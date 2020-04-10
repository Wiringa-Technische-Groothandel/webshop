<?php

declare(strict_types=1);

namespace WTG\Foundation\Auth\Guards;

use Illuminate\Http\Request;
use Laravel\Airlock\Guard;
use Laravel\Airlock\TransientToken;

/**
 * Web Airlock guard.
 *
 * @package     WTG\Foundation\Auth\Guards
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class WebGuard extends Guard
{
    public const GUARD_NAME = 'web';

    /**
     * @inheritDoc
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        if ($user = $this->auth->guard(self::GUARD_NAME)->user()) {
            return $this->supportsTokens($user)
                ? $user->withAccessToken(new TransientToken)
                : $user;
        }

        return parent::__invoke($request);
    }
}