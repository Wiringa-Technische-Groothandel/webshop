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
use WTG\Foundation\Logging\LogManager;
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
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * AbstractService constructor.
     *
     * @param ClientInterface $httpClient
     * @param Application $app
     * @param LogManager $logManager
     */
    public function __construct(ClientInterface $httpClient, Application $app, LogManager $logManager)
    {
        $this->httpClient = $httpClient;
        $this->app = $app;
        $this->logManager = $logManager;
    }

    /**
     * Send the request.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws BindingResolutionException
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

        try {
            $guzzleResponse = $this->httpClient->request(
                $request->type(),
                $request->path(),
                $options
            );
        } catch (GuzzleException $e) {
            $this->logManager->alert($e);

            throw $e;
        }

        return $this->createResponse($request, $guzzleResponse);
    }

    /**
     * Create the response class.
     *
     * @param RequestInterface $request
     * @param GuzzleResponseInterface $guzzleResponse
     * @return ResponseInterface
     * @throws LogicException
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
                    "[WTG RestClient] REST service [%s] does not have a response class or the class does not exist [%s]", // phpcs:ignore
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
