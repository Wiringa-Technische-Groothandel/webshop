<?php

declare(strict_types=1);

namespace WTG\Import\Downloader;

use Exception;
use Generator;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\LogManager;
use WTG\Import\Api\BulkDownloaderInterface;
use WTG\RestClient\Model\Rest\GetProductGroups\Request as GetProductGroupsRequest;
use WTG\RestClient\Model\Rest\GetProductGroups\Response as GetProductGroupsResponse;
use WTG\RestClient\RestManager;

/**
 * Categories downloader.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CategoriesDownloader implements BulkDownloaderInterface
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
                /** @var GetProductGroupsResponse $response */
                $response = $this->restManager->handle(
                    new GetProductGroupsRequest($this->getOffset(), $this->getLimit())
                );

                if ($response->getGroups()->isEmpty()) {
                    break;
                }

                yield $response->getGroups();

                $this->setOffset($this->getOffset() + $this->getLimit());
            }
        } catch (GuzzleException | BindingResolutionException $e) {
            throw new Exception('Product groups download failed', 1578673748, $e);
        }
    }
}
