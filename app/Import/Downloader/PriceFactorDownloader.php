<?php

declare(strict_types=1);

namespace WTG\Import\Downloader;

use Exception;
use Generator;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\LogManager;
use WTG\Import\Api\BulkDownloaderInterface;
use WTG\RestClient\Model\Rest\GetPriceApplications\Request as GetPriceApplicationsRequest;
use WTG\RestClient\Model\Rest\GetPriceApplications\Response as GetPriceApplicationsResponse;
use WTG\RestClient\RestManager;

/**
 * Price factor downloader.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PriceFactorDownloader implements BulkDownloaderInterface
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
     * PriceFactorDownloader constructor.
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
     * Fetch products.
     *
     * @return Generator
     * @throws Exception
     */
    public function download(): iterable
    {
        try {
            while (true) {
                /** @var GetPriceApplicationsResponse $response */
                $response = $this->restManager->handle(
                    new GetPriceApplicationsRequest($this->getOffset(), $this->getLimit(), 'PriceType EQ "VERK"')
                );

                if ($response->priceFactors->isEmpty()) {
                    break;
                }

                yield $response->priceFactors;

                $this->setOffset($this->getOffset() + $this->getLimit());
            }
        } catch (GuzzleException | BindingResolutionException $e) {
            throw new Exception('Price factor download failed', 1576147791, $e);
        }
    }
}
