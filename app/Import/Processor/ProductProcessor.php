<?php

declare(strict_types=1);

namespace WTG\Import\Processor;

use Carbon\Carbon;
use Illuminate\Log\LogManager;
use Illuminate\Support\Str;
use Throwable;
use WTG\Import\Api\ProcessorInterface;
use WTG\Models\Description;
use WTG\Models\Product as ProductModel;
use WTG\RestClient\Model\Rest\ProductResponse;

/**
 * Product data processor.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductProcessor implements ProcessorInterface
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * AbstractProductProcessor constructor.
     *
     * @param LogManager $logManager
     */
    public function __construct(LogManager $logManager)
    {
        $this->logManager = $logManager;
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function process(array $data): void
    {
        $product = $this->fetchProduct($data['sku'], $data['salesUnit']);

        if (! $product && $data['isWeb']) {
            $product = $this->fillModel(new ProductModel(), $data);
        } else {
            $product = $this->fillModel($product, $data);
        }

        if ($product) {
            ProductModel::withoutSyncingToSearch(fn () => $product->saveOrFail());

            $this->setDescription($product, $data['description']);

            if (! $data['isWeb']) {
                $product->delete();

                $this->logManager->debug('[Product processor] Soft-deleted product ' . $data['sku']);
            } else {
                $this->logManager->debug('[Product processor] Imported/updated product ' . $data['sku']);
            }
        }
    }

    /**
     * Find a product for the import process.
     *
     * @param string $sku
     * @param string $unit
     * @return null|ProductModel
     */
    public function fetchProduct(string $sku, string $unit): ?ProductModel
    {
        return app(ProductModel::class)
            ->withTrashed()
            ->where('sku', $sku)
            ->where('sales_unit', $unit)
            ->first();
    }

    /**
     * @param ProductModel $product
     * @param null|string $description
     * @return null|Description
     */
    protected function setDescription(ProductModel $product, ?string $description): ?Description
    {
        $query = Description::query()->where('product_id', $product->getId());

        if (! $description) {
            $query->delete();

            return null;
        }

        $descriptionModel = $query->first();

        if (! $descriptionModel) {
            $descriptionModel = new Description();
        }

        $descriptionModel->setProduct($product);
        $descriptionModel->setValue($description);
        $descriptionModel->save();

        return $descriptionModel;
    }

    /**
     * Fill product model.
     *
     * @param ProductModel $productModel
     * @param array $productData
     * @return ProductModel
     */
    protected function fillModel(ProductModel $productModel, array $productData): ProductModel
    {
        $product = new ProductResponse();
        $product->erpId = (string)$productData['erpId'];
        $product->sku = (string)$productData['sku'];
        $product->name = (string)$productData['name'];
        $product->group = (string)$productData['group'];
        $product->ean = (string)$productData['ean'];
        $product->blocked = (bool)$productData['blocked'];
        $product->inactive = (bool)$productData['inactive'];
        $product->discontinued = (bool)$productData['discontinued'];
        $product->vat = (string)$productData['vat'];
        $product->salesUnit = (string)$productData['salesUnit'];
        $product->packingUnit = (string)$productData['packingUnit'];
        $product->width = (float)$productData['width'];
        $product->length = (float)$productData['length'];
        $product->weight = (float)$productData['weight'];
        $product->height = (float)$productData['height'];
        $product->stockDisplay = (string)$productData['stockDisplay'] ?: 'S';
        $product->minimalPurchase = (float)$productData['minimalPurchase'];
        $product->supplierCode = (string)$productData['supplierCode'];
        $product->brand = (string)$productData['brand'];
        $product->series = (string)$productData['series'];
        $product->type = (string)$productData['type'];
        $product->related = (string)$productData['related'];
        $product->isWeb = (bool)$productData['isWeb'];
        $product->keywords = (string)$productData['keywords'];
        $product->description = (string)$productData['description'];

        foreach (get_object_vars($product) as $key => $value) {
            $snakeKey = Str::snake($key);

            if (in_array($snakeKey, $productModel->getGuarded())) {
                continue;
            }

            $productModel->setAttribute($snakeKey, $value);
        }

        $productModel->setAttribute('synchronized_at', Carbon::createFromTimestamp((int)LARAVEL_START));
        $productModel->setAttribute('deleted_at', null);

        return $productModel;
    }
}
