<?php

declare(strict_types=1);

namespace WTG\Import\Downloader;

use Exception;
use Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\LogManager;
use WTG\Import\Api\BulkDownloaderInterface;
use WTG\RestClient\Model\Rest\GetProducts\Request as GetProductsRequest;
use WTG\RestClient\Model\Rest\GetProducts\Response as GetProductsResponse;
use WTG\RestClient\RestManager;

/**
 * Products downloader.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductsDownloader implements BulkDownloaderInterface
{
    use BulkDownloaderTrait;

    /**
     * @var RestManager
     */
    protected RestManager $restManager;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * ProductsDownloader constructor.
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
     * Download products data.
     *
     * @return Generator
     * @throws Exception
     */
    public function download(): iterable
    {
        try {
            while (true) {
                /** @var GetProductsResponse $response */
                $response = $this->restManager->handle(
                    new GetProductsRequest($this->getOffset(), $this->getLimit())
                );

                if ($response->products->isEmpty()) {
                    break;
                }

                yield $response->products;

                $this->setOffset($this->getOffset() + $this->getLimit());
            }
        } catch (BindingResolutionException $e) {
            throw new Exception('Products download failed', 1576147791, $e);
        }
    }
}
