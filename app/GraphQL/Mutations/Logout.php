<?php

namespace WTG\GraphQL\Mutations;

use WTG\Managers\AuthManager;

class Logout
{
    /**
     * @var AuthManager
     */
    protected AuthManager $authManager;

    /**
     * Logout constructor.
     *
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return void
     */
    public function __invoke($_, array $args): void
    {
        $this->authManager->logout();
    }
}
