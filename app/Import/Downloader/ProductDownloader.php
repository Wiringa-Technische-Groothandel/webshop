<?php

namespace WTG\Import\Downloader;

use Psr\Log\LoggerInterface;
use WTG\Soap\GetProducts\Response\Product;
use WTG\Soap\Service as SoapService;

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
     * @var SoapService
     */
    protected $soapService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Soap constructor.
     *
     * @param SoapService $soapService
     * @param LoggerInterface $logger
     */
    public function __construct(
        SoapService $soapService,
        LoggerInterface $logger
    ) {
        $this->soapService = $soapService;
        $this->logger = $logger;
    }

    /**
     * Fetch products via a SOAP call.
     *
     * @param int $amount
     * @param int $index
     * @return Product[]|null
     * @throws \Exception
     */
    public function fetchProducts(int $amount, int $index): ?array
    {
        $response = $this->soapService->getProducts($amount, $index);

        if ($response->code === 500) {
            throw new \Exception('Product download failed: ' . $response->message);
        }

        return $response->products;
    }
}