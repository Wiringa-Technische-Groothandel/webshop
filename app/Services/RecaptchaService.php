<?php

namespace WTG\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\GuzzleException;

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
    protected $guzzle;

    /**
     * RecaptchaService constructor.
     *
     * @param  Client  $client
     */
    public function __construct(Client $client)
    {
        $this->guzzle = $client;
    }

    /**
     * Check a recaptcha response.
     *
     * @param  string  $response
     * @return bool
     */
    public function check(string $response): bool
    {
        try {
            $guzzleResponse = $this->guzzle->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                RequestOptions::FORM_PARAMS => [
                    'secret' => config('wtg.recaptcha.secret-key'),
                    'response' => $response
                ]
            ]);

            $guzzleResponse = json_decode($guzzleResponse->getBody());
        } catch (GuzzleException $e) {
            \Log::warning($e->getMessage());

            return false;
        }

        return (bool) $guzzleResponse->success;
    }
}