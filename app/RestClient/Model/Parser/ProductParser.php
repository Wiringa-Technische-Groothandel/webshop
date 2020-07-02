<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Parser;

use Illuminate\Support\Facades\Log;
use WTG\RestClient\Model\Rest\ProductResponse;

/**
 * Product parser.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductParser
{
    /**
     * Parse a response product.
     *
     * @param array $item
     * @return ProductResponse
     */
    public function parse(array $item): ProductResponse
    {
        $product = new ProductResponse();
        $product->erpId = $item['id'];
        $product->name = $item['productLanguageNotes'][0]['text'];
        $product->sku = $item['productCode'];
        $product->group = $item['productClassification'][0]['productGroupCode'];
        $product->salesUnit = $item['unitCode'];
        $product->minimalPurchase = $item['minimalPurchase'];
        $product->packingUnit = $item['packingUnit'];
        $product->inactive = $item['inactive'];
        $product->discontinued = $item['discontinued'];
        $product->blocked = $item['blocked'];
        $product->ean = (string)$item['eanCode'];
        $product->length = $item['length'];
        $product->width = $item['width'];
        $product->height = $item['height'] ?? $item['heigth']; // Workaround for typo in JSON response
        $product->weight = $item['weight'];
        $product->vat = $item['vatGroup'];

        if (isset($item['productExtendedLanguageNotes'][0])) {
            $product->description = $item['productExtendedLanguageNotes'][0]['text'];
        }

        $this->mapProductAttributes($product, $item);

        return $product;
    }

    /**
     * Map the product attributes to the response product model.
     *
     * @param ProductResponse $product
     * @param array $item
     * @return void
     */
    protected function mapProductAttributes(ProductResponse $product, array $item): void
    {
        foreach ($item['productAttributes'] as $productAttribute) {
            $value = $productAttribute['value'];

            switch ($productAttribute['attributeCode']) {
                case 'FAB':
                    $product->supplierCode = $value;
                    break;
                case 'MRK':
                    $product->brand = $value;
                    break;
                case 'WBG':
                    $product->series = $value;
                    break;
                case 'WBS':
                    $product->type = $value;
                    break;
                case 'TBH':
                    $product->related = $value ?: null;
                    break;
                case 'WEB':
                    $product->isWeb = (bool)$value;
                    break;
                case 'ATT':
                    $product->keywords = $value;
                    break;
                case 'VRD':
                    $product->stockDisplay = $value;
                    break;
            }
        }
    }
}
