<?php

namespace WTG\Services\Import;

use Carbon\Carbon;
use WTG\Models\ImportData;
use Illuminate\Support\Collection;
use WTG\Models\Product as ProductModel;
use WTG\Soap\GetProducts\Response\Product;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Assortment import.
 *
 * @package     WTG\Console
 * @subpackage  Commands\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Assortment
{
    const FILENAME_PATTERN = '/compleet(?P<date>[0-9]{14})(?P<item>[0-9]{6})\.xml$/';
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
     */
    public function run(): void
    {
        $newUploads = $this->getNewUploads();

        if ($newUploads->isEmpty()) {
            return;
        }

        $newUploads->each(function (Collection $fileGroup) {
            $fileGroup->get('files')->each(function ($filename) {
                $xml = simplexml_load_string(
                    $this->readFile($filename)
                );

                $this->importProducts($xml);
            });
        });
    }

    /**
     * Get the newly uploaded file groups.
     *
     * @return Collection
     */
    public function getNewUploads(): Collection
    {
        return $this->getFileList()->filter(function (Collection $fileGroup) {
            return $fileGroup->get('date')->timestamp > $this->getLastImportDate()->timestamp;
        });
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
            )->map(function ($filename) {
                if (! preg_match(self::FILENAME_PATTERN, $filename, $match)) {
                    return null;
                }

                return collect([
                    'filename' => $filename,
                    'date' => Carbon::createFromFormat('YmdHis', $match['date'], 'Europe/Amsterdam'),
                    'item' => (int) $match['item']
                ]);
            })
            ->filter()
            ->groupBy(function (Collection $item) {
                return $item->get('date')->timestamp;
            })
            ->map(function (Collection $fileGroup) {
                $files = $fileGroup->map(function (Collection $file) {
                    return $file->get('filename');
                });

                return collect([
                    'files' => $files,
                    'count' => $files->count(),
                    'date'  => $fileGroup->first()->get('date')
                ]);
            });

        return $this->files;
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
     * Get the name of the last import date.
     *
     * @return Carbon
     */
    public function getLastImportDate(): Carbon
    {
        $value = ImportData::key(ImportData::KEY_LAST_ASSORTMENT_RUN_TIME)->value('value');

        if ($value === null) {
            // If no import has ever been executed, default to 1970-01-01
            return Carbon::createFromDate(1970, 1, 1);
        }

        return Carbon::parse($value);
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
                \Log::debug(sprintf('[Product import] Skipped %s. Reason: %s', $sku, 'products is not enabled for the webshop'));

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
     * @return void
     */
    public function updateImportData(): void
    {
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