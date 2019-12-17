<?php

declare(strict_types=1);

namespace WTG\RestClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use LogicException;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\RequestInterface;
use WTG\RestClient\Api\Model\ResponseInterface;
use WTG\RestClient\Api\RestManagerInterface;

/**
 * Rest manager.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class RestManager implements RestManagerInterface
{
    /**
     * @var ClientInterface
     */
    protected ClientInterface $httpClient;

    /**
     * @var Application
     */
    protected Application $app;

    /**
     * AbstractService constructor.
     *
     * @param ClientInterface $httpClient
     * @param Application $app
     */
    public function __construct(ClientInterface $httpClient, Application $app)
    {
        $this->httpClient = $httpClient;
        $this->app = $app;
    }

    /**
     * Send the request.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws BindingResolutionException
     * @throws GuzzleException
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        $options = [];

        if ($request->params()) {
            $options[RequestOptions::QUERY] = $request->params();
        }

        if ($request->headers()) {
            $options[RequestOptions::HEADERS] = $request->headers();
        }

        if (
            $request->type() === RequestInterface::HTTP_REQUEST_TYPE_PUT ||
            $request->type() === RequestInterface::HTTP_REQUEST_TYPE_POST
        ) {
            if ($request->body()) {
                $options[RequestOptions::JSON] = $request->body();
            }
        }

        $guzzleResponse = $this->httpClient->request(
            $request->type(),
            $request->path(),
            $options
        );

        return $this->createResponse($request, $guzzleResponse);
    }

    /**
     * Create the response class.
     *
     * @param RequestInterface $request
     * @param GuzzleResponseInterface $guzzleResponse
     * @return ResponseInterface
     * @throws BindingResolutionException
     */
    protected function createResponse(
        RequestInterface $request,
        GuzzleResponseInterface $guzzleResponse
    ): ResponseInterface {
        $fullNamespace = get_class($request);
        $namespace = substr($fullNamespace, 0, strrpos($fullNamespace, '\\'));
        $generatedResponseClassNamespace = sprintf('%s\\Response', $namespace);

        if (! class_exists($generatedResponseClassNamespace)) {
            throw new LogicException(
                sprintf(
                    "[WTG RestClient] REST service [%s] does not have a response class or the class does not exist [%s]",
                    substr($namespace, strrpos($namespace, '\\') + 1),
                    $generatedResponseClassNamespace
                )
            );
        }

        return $this->app->make(
            $generatedResponseClassNamespace,
            [
                'guzzleResponse' => $guzzleResponse,
            ]
        );
    }
}
