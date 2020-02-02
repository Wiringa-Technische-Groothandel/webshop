<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\Unit\TestCase;
use WTG\Services\RecaptchaService;

/**
 * Recaptcha service test.
 *
 * @package     Tests\Unit
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RecaptchaServiceTest extends TestCase
{
    /**
     * @test
     * @return void
     * @throws BindingResolutionException
     */
    public function testCheckReturnValues(): void
    {
        $mock = new MockHandler(
            [
                new Response(200, [], json_encode(['success' => false])),
                new Response(200, [], json_encode(['success' => true])),
                new RequestException("Error Communicating with Server", new Request('POST', 'test')),
            ]
        );

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = $this->app->make(
            RecaptchaService::class,
            [
                'client' => $client,
            ]
        );

        $this->assertFalse($service->check('foobar'));
        $this->assertTrue($service->check('foobar'));
        $this->assertFalse($service->check('foobar'));
    }
}
