<?php

namespace WTG\Services\Import;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use WTG\Models\ImportData;
use Illuminate\Support\Collection;
use WTG\Models\Product as ProductModel;
use WTG\Soap\GetProducts\Response\Product;
use Illuminate\Filesystem\FilesystemManager;

/**
 * Assortment import.
 *
 * @package     WTG\Console
 * @subpackage  Commands\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Assortment
{
    const FILENAME_PATTERN = '/Compleet[0-9]+\.xml$/';
    const MUTATION_DELETE = 'D';
    const MUTATION_UPDATE = 'U';

    /**
     * @var FilesystemManager
     */
    protected $fs;

    /**
     * @var Collection
     */
    protected $files;

    /**
     * @var Carbon
     */
    protected $runTime;

    /**
     * Assortment constructor.
     *
     * @param  FilesystemManager  $fs
     * @param  Carbon  $carbon
     */
    public function __construct(FilesystemManager $fs, Carbon $carbon)
    {
        $this->fs = $fs;
        $this->runTime = $carbon;
    }

    /**
     * Run the importer.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function run(): void
    {
        $files = $this->getFileList();
        $lastImportedFile = $this->getLastImportedFile();
        $newestFile = $this->getNewestFile();

        if ($newestFile === $lastImportedFile) {
            return;
        }

        $index = $this->getStartFromIndex();

        while ($files->has($index)) {
            $file = $files->get($index);
            $products = simplexml_load_string(
                $this->readFile($file)
            );

            $this->importProducts($products);

            $index++;
        }

        $this->updateImportData($file);
    }

    /**
     * Get the file list from the ftp location.
     *
     * @param  bool  $force
     * @return \Illuminate\Support\Collection
     */
    public function getFileList(bool $force = false): Collection
    {
        if ($this->files && !$force) {
            return $this->files;
        }

        $this->files = collect(
                $this->fs->disk('sftp')->allFiles('assortment')
            )->filter(function ($filename) {
                return preg_match(self::FILENAME_PATTERN, $filename);
            });

        return $this->files;
    }

    /**
     * Get the newest file name from the list.
     *
     * @return string
     */
    public function getNewestFile(): string
    {
        return $this->getFileList()->last() ?: '';
    }

    /**
     * Get the start index based on the last imported file.
     *
     * @return int
     */
    public function getStartFromIndex(): int
    {
        return (int) $this->getFileList()->search(
            $this->getLastImportedFile()
        );
    }

    /**
     * Read a file from the SFTP location.
     *
     * @param  string  $file
     * @return string
     * @throws FileNotFoundException
     */
    public function readFile(string $file): string
    {
        return $this->fs->disk('sftp')->get($file);
    }

    /**
     * Get the name of the last imported file.
     *
     * @return string|null
     */
    public function getLastImportedFile(): ?string
    {
        return ImportData::key(ImportData::KEY_LAST_ASSORTMENT_FILE)->value('value');
    }

    /**
     * Import products.
     *
     * @param  \SimpleXMLElement  $products
     * @param  null|int  $count
     * @return void
     */
    public function importProducts(\SimpleXMLElement $products, ?int &$count = null)
    {
        foreach ($products->children() as $xmlProduct) {
            $mutation = substr((string) $xmlProduct->Mutation, 0, 1) ?: self::MUTATION_UPDATE;
            $sku = (string) $xmlProduct->ProductId;
            $unit = (string) $xmlProduct->UnitId;

            if ($mutation === self::MUTATION_DELETE) {
                $product = $this->findProduct($sku, $unit);

                if (! $product) {
                    continue;
                }

                $product->setAttribute('synchronized_at', $this->runTime);
                $product->setAttribute('deleted_at', $this->runTime);
                $product->save();

                \Log::debug(sprintf('[Product import] Deleted %s.', $sku));

                continue;
            }

            /** @var Product $soapProduct */
            $soapProduct = app()->make(Product::class);

            $soapProduct->sku             = $sku;
            $soapProduct->name            = (string) $xmlProduct->ShortDescriptions->ShortDescription->Description;
            $soapProduct->group           = (string) $xmlProduct->Categories->Category->Groups->Group->ProdGrpId;
            $soapProduct->ean             = (string) $xmlProduct->EanCode;
            $soapProduct->blocked         = ((string) $xmlProduct->Blocked) === "true";
            $soapProduct->inactive        = ((string) $xmlProduct->Inactive) === "true";
            $soapProduct->discontinued    = ((string) $xmlProduct->Discontinued) === "true";
            $soapProduct->vat             = (float) $xmlProduct->VatPercentage;
            $soapProduct->sales_unit      = $unit;
            $soapProduct->packing_unit    = (string) $xmlProduct->PackagingUnitId;
            $soapProduct->width           = (float) $xmlProduct->ProductWidthCm;
            $soapProduct->length          = (float) $xmlProduct->ProductLengthCm;
            $soapProduct->weight          = (float) $xmlProduct->ProductWeightKg;
            $soapProduct->height          = (float) $xmlProduct->ProductHeightCm;

            $this->assignAttributes($soapProduct, $xmlProduct);

            // Skip this product
            if (! $soapProduct->webshop) {
                \Log::debug(sprintf('[Product import] Skipped %s. Reason: %s', $sku, 'webshop is false'));

                continue;
            }

            $product = ProductModel::createFromSoapProduct($soapProduct);
            $product->setAttribute('synchronized_at', $this->runTime);
            $product->setAttribute('deleted_at',
                ($soapProduct->inactive || $soapProduct->blocked || $soapProduct->discontinued) ? Carbon::now() : null
            );

            $product->save();

            if ($count !== null) {
                $count++;
            }

            \Log::debug(sprintf('[Product import] Imported %s.', $sku));
        }
    }

    /**
     * Update the last import data.
     *
     * @param  string  $file
     * @return void
     */
    public function updateImportData(string $file): void
    {
        ImportData::key(ImportData::KEY_LAST_ASSORTMENT_FILE)->update([
            'value' => $file ?? $this->getLastImportedFile()
        ]);

        ImportData::key(ImportData::KEY_LAST_ASSORTMENT_RUN_TIME)->update([
            'value' => $this->runTime
        ]);
    }

    /**
     * Assign product attributes.
     *
     * @param  Product  $responseProduct
     * @param  \SimpleXMLElement  $product
     * @return void
     */
    public function assignAttributes(Product &$responseProduct, $product): void
    {
        foreach ($product->Attributes->children() as $attribute) {
            $value = (string) $attribute->AttributeValues->AttributeValue->NativeDescription;

            switch ($attribute->AttributeId) {
                case 'MRK':
                    $responseProduct->brand = $value;
                    break;
                case 'WBG':
                    $responseProduct->series = $value;
                    break;
                case 'WBS':
                    $responseProduct->type = $value;
                    break;
                case 'TBH':
                    $responseProduct->related = $value;
                    break;
                case 'WEB':
                    $responseProduct->webshop = (bool) $value;
                    break;
                case 'ATT':
                    $responseProduct->keywords = $value;
                    break;
            }
        }
    }

    /**
     * Find a product for the import process.
     *
     * @param  string  $sku
     * @param  string  $unit
     * @return null|Product
     */
    public static function findProduct(string $sku, string $unit): ?Product
    {
        return app()->make(ProductModel::class)
            ->where('sku', $sku)
            ->where('sales_unit', $unit)
            ->first();
    }
}