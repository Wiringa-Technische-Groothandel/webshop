<?php

declare(strict_types=1);

namespace WTG\Import\Processor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Log\LogManager;
use WTG\Catalog\Model\PriceFactor;
use WTG\Import\Api\ProcessorInterface;
use WTG\Models\Product;

/**
 * Product data processor.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PriceFactorProcessor implements ProcessorInterface
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
     */
    public function process(array $data): void
    {
        $priceFactor = $this->fetchModel($data['erpId']);

        if (! $priceFactor) {
            $priceFactor = new PriceFactor();
        }

        try {
            $this->fillModel($priceFactor, $data);

            $this->logManager->debug('[Price factor processor] Imported/updated price factor ' . $data['erpId']);
        } catch (ModelNotFoundException $exception) {
            $this->logManager->debug(
                '[Price factor processor] Skipped price factor ' . $data['erpId'] . ': Referenced product does not exist'
            );
        }
    }

    /**
     * Fetch model.
     *
     * @param string $erpId
     * @return null|PriceFactor
     */
    protected function fetchModel(string $erpId): ?PriceFactor
    {
        return PriceFactor::query()->where('erp_id', $erpId)->first();
    }

    /**
     * Fill price factor model.
     *
     * @param PriceFactor $priceFactor
     * @param array $data
     * @return bool
     */
    protected function fillModel(PriceFactor $priceFactor, array $data): bool
    {
        $product = Product::findBySku($data['sku'], true);

        $priceFactor->setErpId($data['erpId']);
        $priceFactor->setPriceFactor((float)$data['priceUnitFactor']);
        $priceFactor->setPricePer((float)$data['pricePer']);
        $priceFactor->setPriceUnit($data['priceUnit']);
        $priceFactor->setScaleUnit($data['scaleUnit']);
        $priceFactor->setProduct($product);

        $priceFactor->setAttribute('synchronized_at', Carbon::createFromTimestamp((int)LARAVEL_START));

        return $priceFactor->save();
    }
}
