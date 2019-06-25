<?php

namespace WTG\Import\Importer;

use Carbon\Carbon;

use Psr\Log\LoggerInterface;

use WTG\Models\Product as ProductModel;
use WTG\Soap\GetProducts\Response\Product;

abstract class ProductImporter implements ImporterInterface
{
    /**
     * @var Carbon
     */
    protected $runTime;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ProductImporter constructor.
     *
     * @param Carbon $carbon
     * @param LoggerInterface $logger
     */
    public function __construct(Carbon $carbon, LoggerInterface $logger)
    {
        $this->runTime = $carbon->now();
        $this->logger = $logger;
    }

    /**
     * Find a product for the import process.
     *
     * @param  string  $sku
     * @param  string  $unit
     * @return null|ProductModel
     */
    public static function fetchProduct(string $sku, string $unit): ?ProductModel
    {
        return app(ProductModel::class)
            ->withTrashed()
            ->where('sku', $sku)
            ->where('sales_unit', $unit)
            ->first();
    }

    /**
     * Create a new product.
     *
     * @param array $productData
     * @return void
     */
    protected function createProduct(array $productData): void
    {
        $product = new ProductModel;

        $this->fillModel($product, $productData);

        $product->setAttribute('synchronized_at', $this->runTime);
        $product->save();
    }

    /**
     * Update an existing product.
     *
     * @param ProductModel $product
     * @param array $productData
     * @return void
     */
    protected function updateProduct(ProductModel $product, array $productData): void
    {
        $this->fillModel($product, $productData);

        $product->setAttribute('synchronized_at', $this->runTime);
        $product->setAttribute('deleted_at', null);

        $product->save();
    }

    protected function fillModel(ProductModel $product, array $productData)
    {
        /** @var Product $soapProduct */
        $soapProduct = app(Product::class);
        $soapProduct->sku               = (string) $productData['sku'];
        $soapProduct->name              = (string) $productData['name'];
        $soapProduct->group             = (string) $productData['group'];
        $soapProduct->ean               = (string) $productData['ean'];
        $soapProduct->blocked           = (bool) $productData['blocked'];
        $soapProduct->inactive          = (bool) $productData['inactive'];
        $soapProduct->discontinued      = (bool) $productData['discontinued'];
        $soapProduct->vat               = (float) $productData['vat'];
        $soapProduct->sales_unit        = (string) $productData['sales_unit'];
        $soapProduct->packing_unit      = (string) $productData['packing_unit'];
        $soapProduct->width             = (float) $productData['width'];
        $soapProduct->length            = (float) $productData['length'];
        $soapProduct->weight            = (float) $productData['weight'];
        $soapProduct->height            = (float) $productData['height'];
        $soapProduct->stock_display     = (string) $productData['stock_display'] ?: 'S';
        $soapProduct->supplier_code     = (string) $productData['supplier_code'];
        $soapProduct->brand             = (string) $productData['brand'];
        $soapProduct->series            = (string) $productData['series'];
        $soapProduct->type              = (string) $productData['type'];
        $soapProduct->related           = (string) $productData['related'];
        $soapProduct->webshop           = (bool) $productData['webshop'];
        $soapProduct->keywords          = (string) $productData['keywords'];

        foreach (get_object_vars($soapProduct) as $key => $value) {
            if (in_array($key, $product->getGuarded())) {
                continue;
            }

            $product->setAttribute($key, $value);
        }

        return $product;
    }

    /**
     * Delete products that were not present in the import file.
     *
     * @param Carbon $before
     * @return void
     */
    protected function deleteProducts(Carbon $before): void
    {
        $deleteCount = app(ProductModel::class)
            ->where('synchronized_at', '<', $before)
            ->delete();

        $this->logger->info('[Product import] Deleted ' . $deleteCount . ' products');
    }
}