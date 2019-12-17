<?php

declare(strict_types=1);

namespace WTG\Services;

use SoapClient;
use SoapFault;

/**
 * Soap service.
 *
 * @package     WTG
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AbstractSoapService
{
    /**
     * @var SoapClient
     */
    protected $client;

    /**
     * Forward function calls to the soap client.
     *
     * @param string $action
     * @param array $arguments
     * @return mixed
     * @throws SoapFault
     */
    public function soapCall(string $action, array $arguments)
    {
        return call_user_func_array([$this->getClient(), $action], [$action => $arguments]);
    }

    /**
     * Return the soap client.
     *
     * @return SoapClient
     * @throws SoapFault
     */
    public function getClient(): SoapClient
    {
        if (! $this->client) {
            $this->client = new SoapClient(
                config('soap.wsdl'),
                [
                'exceptions' => false,
                ]
            );
        }

        return $this->client;
    }
}
