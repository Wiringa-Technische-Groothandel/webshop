<?php

declare(strict_types=1);

namespace WTG\Import\Downloader;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\LogManager;
use WTG\Import\Api\DownloaderInterface;
use WTG\Managers\RestManager;
use WTG\RestClient\Api\Model\ResponseInterface;
use WTG\RestClient\Model\Rest\GetProduct\Request as GetProductRequest;
use WTG\RestClient\Model\Rest\GetProduct\Response as GetProductResponse;

/**
 * Product downloader.
 *
 * @package     WTG\Import
 * @subpackage  Downloader
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductDownloader implements DownloaderInterface
{
    /**
     * @var RestManager
     */
    protected RestManager $restManager;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * @var string
     */
    protected string $id;

    /**
     * ProductDownloader constructor.
     *
     * @param RestManager $restManager
     * @param LogManager $logManager
     */
    public function __construct(RestManager $restManager, LogManager $logManager)
    {
        $this->restManager = $restManager;
        $this->logManager = $logManager;
    }

    /**
     * Download product data.
     *
     * @return GetProductResponse
     * @throws Exception
     */
    public function download(): ResponseInterface
    {
        try {
            /** @var GetProductResponse $response */
            $response = $this->restManager->handle(
                new GetProductRequest($this->id)
            );
        } catch (GuzzleException | BindingResolutionException $e) {
            throw new Exception('Product download failed', 1576253618, $e);
        }

        return $response;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
