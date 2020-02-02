<?php

declare(strict_types=1);

namespace WTG\RestClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * RestClient service provider.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            ClientInterface::class,
            function () {
                return new Client(
                    [
                        'base_uri'    => rtrim(config('wtg.rest.base_url', ''), '/') . '/',
                        'timeout'     => 30,
                        'http_errors' => true,
                        'auth'        => [
                            config('wtg.rest.auth.username'),
                            config('wtg.rest.auth.password'),
                        ],
                    ]
                );
            }
        );
    }
}
