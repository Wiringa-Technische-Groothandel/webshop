<?php

namespace WTG\Import\Importer;

use Exception;

/**
 * CSV Product importer.
 *
 * @package     WTG\Import
 * @subpackage  Importer
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CsvProductImporter extends ProductImporter
{
    /**
     * Run the importer.
     *
     * @param string $filePath
     * @return void
     * @throws Exception
     */
    public function execute(string $filePath): void
    {
        $f = fopen($filePath, 'r');
        $importCount = 0;

        $header = fgetcsv($f);

        while ($csvData = fgetcsv($f)) {
            $productData = array_combine($header, $csvData);

            if (! $productData) {
                throw new Exception('Failed to combine file header with csv data');
            }

            $product = $this->fetchProduct($productData['sku'], $productData['sales_unit']);

            if (! $product) {
                $this->createProduct($productData);
            } else {
                $this->updateProduct($product, $productData);
            }

            $importCount++;
        }

        fclose($f);

        $this->logger->info('[Product import] Imported ' . $importCount . ' products');

        $this->deleteProducts($this->runTime);
    }
}