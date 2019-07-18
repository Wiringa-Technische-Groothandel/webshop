<?php

namespace WTG\Import\Importer;

/**
 * Single product importer.
 *
 * @package     WTG\Import
 * @subpackage  Importer
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SingleProductImporter extends ProductImporter
{
    /**
     * Run the importer.
     *
     * @param array $productData
     * @return void
     */
    public function execute(array $productData): void
    {
        $product = $this->fetchProduct($productData['sku'], $productData['sales_unit']);

        if ($productData['webshop']) {
            if (! $product) {
                $this->createProduct($productData);
            } else {
                $this->updateProduct($product, $productData);
            }
        }

        $this->logger->info('[Single product import] Imported/updated product ' . $productData['sku']);
    }
}