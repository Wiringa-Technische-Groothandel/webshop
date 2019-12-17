<?php

declare(strict_types=1);

namespace WTG\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Log\LogManager;

/**
 * Recaptcha service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RecaptchaService
{
    /**
     * @var Client
     */
    protected Client $guzzle;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * RecaptchaService constructor.
     *
     * @param Client $client
     * @param LogManager $logManager
     */
    public function __construct(Client $client, LogManager $logManager)
    {
        $this->guzzle = $client;
        $this->logManager = $logManager;
    }

    /**
     * Check a recaptcha response.
     *
     * @param string $response
     * @return bool
     */
    public function check(string $response): bool
    {
        try {
            $guzzleResponse = $this->guzzle->request(
                'POST',
                'recaptcha/api/siteverify',
                [
                    RequestOptions::FORM_PARAMS => [
                        'secret'   => config('wtg.recaptcha.secret-key'),
                        'response' => $response,
                    ],
                ]
            );

            $guzzleResponse = json_decode((string)$guzzleResponse->getBody());
        } catch (GuzzleException $exception) {
            $this->logManager->warning($exception);

            return false;
        }

        return (bool)$guzzleResponse->success;
    }
}
