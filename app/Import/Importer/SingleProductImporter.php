<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\LogManager;
use Throwable;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\ProductManager;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\Downloader\ProductDownloader;
use WTG\Import\Processor\ProductProcessor;

/**
 * Single product importer.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SingleProductImporter implements ImporterInterface
{
    protected ProductDownloader $downloader;
    protected ProductProcessor $processor;
    protected LogManager $logManager;
    protected DatabaseManager $databaseManager;
    protected ProductManager $productManager;

    private ?string $sku = null;
    private ?string $erpIp = null;

    /**
     * MultiProductImporter constructor.
     *
     * @param ProductDownloader $downloader
     * @param ProductProcessor $processor
     * @param LogManager $logManager
     * @param DatabaseManager $databaseManager
     * @param ProductManager $productManager
     */
    public function __construct(
        ProductDownloader $downloader,
        ProductProcessor $processor,
        LogManager $logManager,
        DatabaseManager $databaseManager,
        ProductManager $productManager
    ) {
        $this->downloader = $downloader;
        $this->processor = $processor;
        $this->logManager = $logManager;
        $this->databaseManager = $databaseManager;
        $this->productManager = $productManager;
    }

    /**
     * Run the importer.
     *
     * @return void
     * @throws Throwable
     */
    public function import(): void
    {
        $product = null;

        try {
            $this->databaseManager->beginTransaction();

            if ($this->sku) {
                $this->logManager->info("[Single product importer] Loading product from database");
                $product = $this->productManager->find($this->sku, ProductInterface::FIELD_SKU, true);

                $this->erpIp = $product->getErpId();
            }

            if (! $this->erpIp) {
                throw new \Exception("Missing erp ID");
            }

            $this->logManager->info("[Single product importer] Fetching fresh product data from API");
            $this->downloader->setId($this->erpIp);

            $downloadResponse = $this->downloader->download();

            $responseProduct = $downloadResponse->product;

            $this->logManager->info("[Single product importer] Processing response product data");
            $this->processor->process(
                array_merge(get_class_vars(get_class($responseProduct)), get_object_vars($responseProduct))
            );

            $this->databaseManager->commit();
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }
    }

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @param string $erpIp
     * @return $this
     */
    public function setErpIp(string $erpIp): self
    {
        $this->erpIp = $erpIp;

        return $this;
    }
}
