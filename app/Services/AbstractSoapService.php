<?php

namespace WTG\Services;

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
     * @var \SoapClient
     */
    protected $client;

    /**
     * SoapService constructor.
     *
     * @param  \SoapClient  $client
     */
    public function __construct(\SoapClient $client)
    {
        $this->client = $client;
    }

    /**
     * Return the soap client.
     *
     * @return \SoapClient
     */
    public function getClient(): \SoapClient
    {
        return $this->client;
    }

    /**
     * Forward function calls to the soap client.
     *
     * @param  string  $action
     * @param  array  $arguments
     * @return mixed
     */
    public function soapCall(string $action, array $arguments)
    {
        return call_user_func_array([$this->client, $action], [ $action => $arguments ]);
    }
}