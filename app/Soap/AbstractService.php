<?php

declare(strict_types=1);

namespace WTG\Soap;

/**
 * Abstract service.
 *
 * @package     WTG
 * @subpackage  Soap
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class AbstractService
{
    /**
     * Send the request.
     *
     * @param  string  $action
     * @param  AbstractRequest  $request
     * @return AbstractResponse
     */
    protected function sendRequest(string $action, AbstractRequest $request)
    {
        $properties = $this->createPropertyMap($request);

        if (config('soap.logging')) {
            \Log::debug(sprintf('Send SOAP request for %s', $action), [
                'request' => $properties
            ]);
        }

        $response = app('soap')->soapCall($action, $properties);

        if (config('soap.logging')) {
            \Log::debug(sprintf('Received SOAP response for %s', $action), [
                'response' => $response
            ]);
        }

        return $response;
    }

    /**
     * Create a property map from an object.
     *
     * @param  object  $object
     * @return array
     */
    protected function createPropertyMap($object)
    {
        $properties = get_object_vars($object);

        foreach ($properties as $key => $value) {
            if (is_object($value)) {
                $value = $this->createPropertyMap($value);
            } elseif (is_array($value)) {
                foreach ($value as $innerKey => $innerValue) {
                    $value[$innerKey] = $this->createPropertyMap($innerValue);
                }
            }

            $properties[ucfirst($key)] = $value;

            unset($properties[$key]);
        }

        return $properties;
    }
}