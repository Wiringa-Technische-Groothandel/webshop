<?php

declare(strict_types=1);

namespace WTG\GraphQL\Mutations;

use GraphQL\Error\Error;
use WTG\Managers\AuthManager;
use WTG\Managers\DeviceManager;

class Login
{
    protected AuthManager $authManager;
    /**
     * @var DeviceManager
     */
    protected DeviceManager $deviceManager;

    /**
     * Login constructor.
     *
     * @param AuthManager $authManager
     * @param DeviceManager $deviceManager
     */
    public function __construct(AuthManager $authManager, DeviceManager $deviceManager)
    {
        $this->authManager = $authManager;
        $this->deviceManager = $deviceManager;
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return array<string, string>
     * @throws Error
     */
    public function __invoke($_, array $args)
    {
        $customer = $this->authManager->authenticate(
            $args['customerNumber'],
            $args['username'],
            $args['password']
        );

        if (! $customer) {
            throw new Error('Invalid credentials.');
        }

        $token = $customer->createToken(
            sprintf(
                '%s %s',
                $this->deviceManager->getBrand(),
                $this->deviceManager->getOS()
            )
        );

        return [
            'token' => $token->plainTextToken
        ];
    }
}
