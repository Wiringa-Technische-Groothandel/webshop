<?php

declare(strict_types=1);

namespace WTG\Services\Import;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\FilesystemManager;
use WTG\Models\Discount;

class Discounts
{
    public const FILE_PATH = 'import/discount.csv';
    public const PROCESSED_DIR = 'storage/app/import/processed/';

    public const GROUP_DISCOUNT_TABLE = 'VA-220';
    public const PRODUCT_DISCOUNT_TABLE = 'VA-260';
    public const DEFAULT_GROUP_DISCOUNT_TABLE = 'VA-221';
    public const DEFAULT_PRODUCT_DISCOUNT_TABLE = 'VA-261';

    /**
     * @var FilesystemManager
     */
    protected $fs;

    /**
     * @var DatabaseManager
     */
    protected $dm;

    /**
     * Discounts constructor.
     *
     * @param FilesystemManager $fs
     * @param DatabaseManager $dm
     */
    public function __construct(FilesystemManager $fs, DatabaseManager $dm)
    {
        $this->fs = $fs;
        $this->dm = $dm;
    }

    /**
     * Run the importer.
     *
     * @return bool
     * @throws FileNotFoundException
     */
    public function run(): bool
    {
        if (! $this->fs->disk('local')->exists(self::FILE_PATH)) {
            return false;
        }

        $fileResource = $this->fs->disk('local')->readStream(self::FILE_PATH);

        $this->dm->table('discounts')->truncate();

        while (($data = fgetcsv($fileResource, 0, ';')) !== false) {
            $this->dm->table('discounts')->insert(
                [
                    'importance'   => $this->getImportance($data[0]),
                    'company_id'   => ($data[1] !== '' ? $data[1] : null),
                    'product'      => $data[2],
                    'start_date'   => Carbon::createFromFormat('d-m-Y H:i:s', $data[3]),
                    'end_date'     => Carbon::createFromFormat('d-m-Y H:i:s', $data[4]),
                    'discount'     => (float)str_replace(',', '.', $data[5]),
                    'group_desc'   => $data[6],
                    'product_desc' => $data[7],
                ]
            );
        }

        fclose($fileResource);

        return true;
    }

    /**
     * Convert the table to an importance level.
     *
     * @param string $table
     * @return int
     * @throws Exception
     */
    protected function getImportance(string $table): int
    {
        switch ($table) {
            case self::GROUP_DISCOUNT_TABLE:
                return Discount::IMPORTANCE_GROUP;
            case self::PRODUCT_DISCOUNT_TABLE:
                return Discount::IMPORTANCE_CUSTOMER;
            case self::DEFAULT_GROUP_DISCOUNT_TABLE:
                return Discount::IMPORTANCE_GENERIC;
            case self::DEFAULT_PRODUCT_DISCOUNT_TABLE:
                return Discount::IMPORTANCE_PRODUCT;
            default:
                throw new Exception('Unknown table ' . $table);
        }
    }
}
