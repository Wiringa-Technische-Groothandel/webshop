<?php

namespace Tests\Unit\Services;

use GuzzleHttp\Client;
use Tests\Unit\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use WTG\Services\RecaptchaService;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;

/**
 * Recaptcha service test.
 *
 * @package     Tests\Unit
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RecaptchaServiceTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testCheckReturnValues(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['success' => false])),
            new Response(200, [], json_encode(['success' => true])),
            new RequestException("Error Communicating with Server", new Request('POST', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new RecaptchaService($client);

        $this->assertFalse($service->check('foobar'));
        $this->assertTrue($service->check('foobar'));
        $this->assertFalse($service->check('foobar'));
    }
}