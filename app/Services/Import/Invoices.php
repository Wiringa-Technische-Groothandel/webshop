<?php

declare(strict_types=1);

namespace WTG\Services\Import;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use WTG\Models\Company;

/**
 * Invoice import.
 *
 * @package     WTG\Console
 * @subpackage  Commands\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Invoices
{
    // phpcs:ignore
    public const FILENAME_PATTERN = '/(?P<invoice>[0-9]{8})_(?P<customer>[0-9]{5})_(?P<date>[0-9]{8})_(?P<time>[0-9]{6})\.PDF$/';
    public const SORT_ORDER_ASC = 1;
    public const SORT_ORDER_DESC = 2;
    public const CACHE_KEY = 'invoice_files';

    protected FilesystemManager $fs;
    protected Collection $files;
    protected CarbonImmutable $runTime;

    /**
     * Assortment constructor.
     *
     * @param FilesystemManager $fs
     * @param CarbonImmutable $carbon
     */
    public function __construct(FilesystemManager $fs, CarbonImmutable $carbon)
    {
        $this->fs = $fs;
        $this->runTime = $carbon;
    }

    /**
     * Get the invoice collection for a customer.
     *
     * @param string $customerNumber
     * @param bool $sort
     * @param int $sortOrder
     * @return Collection
     */
    public function getForCustomer(
        string $customerNumber,
        bool $sort = true,
        int $sortOrder = self::SORT_ORDER_DESC
    ): Collection {
        /** @var Collection $files */
        $files = $this->getFileList()->get($customerNumber, collect());

        if ($sort) {
            $files = $files->sortBy(
                function (Collection $file) {
                    /** @var CarbonImmutable $date */
                    $date = $file->get('date');

                    return $date->timestamp;
                }
            );
        }

        if ($sortOrder === self::SORT_ORDER_DESC) {
            $files = $files->reverse();
        }

        return $files;
    }

    /**
     * Get the file list from the ftp location.
     *
     * @param bool $rebuildCache
     * @return Collection
     */
    public function getFileList(bool $rebuildCache = false): Collection
    {
        if ($this->files && ! $rebuildCache) {
            return $this->files;
        }

        if ($rebuildCache) {
            return $this->rebuildCache();
        }

        $this->files = Cache::rememberForever(
            self::CACHE_KEY,
            function () {
                return $this->getInvoiceFileList();
            }
        );

        return $this->files;
    }

    /**
     * Rebuild the invoice file list cache.
     *
     * @return Collection
     */
    public function rebuildCache(): Collection
    {
        return tap($this->getInvoiceFileList(), function ($files) {
            Cache::put(self::CACHE_KEY, $files);
        });
    }

    /**
     * Read a file from the SFTP location.
     *
     * @param string $file
     * @return string
     * @throws FileNotFoundException
     */
    public function readFile(string $file): string
    {
        return $this->fs->disk('sftp')->get($file);
    }

    /**
     * Get the invoice file list from the FTP disk.
     *
     * @return Collection
     */
    private function getInvoiceFileList(): Collection
    {
        return collect(
            $this->fs->disk('sftp')->allFiles('invoices')
        )->map(
            function ($filename) {
                if (! preg_match(self::FILENAME_PATTERN, $filename, $match)) {
                    Log::notice(
                        sprintf(
                            "%s: Removing file '%s' because the name does not match the given pattern.",
                            __CLASS__,
                            $filename
                        )
                    );

                    // Remove the files that should not be in the folder
                    $this->fs->disk('sftp')->delete($filename);

                    return null;
                }

                return collect(
                    [
                        'filename' => $filename,
                        'customer' => (int)$match['customer'],
                        'invoice'  => (int)$match['invoice'],
                        'date'     => CarbonImmutable::createFromFormat('dmYHis', ($match['date'] . $match['time'])),
                    ]
                );
            }
        )
            ->filter()
            ->groupBy(
                function (Collection $item) {
                    return $item->get('customer');
                }
            )
            ->map(
                function (Collection $fileGroup, int $customerNumber) {
                    $files = $fileGroup->mapWithKeys(
                        function (Collection $file) {
                            return [
                                $file->get('invoice') => $file->only(
                                    [
                                        'filename',
                                        'invoice',
                                        'date',
                                    ]
                                ),
                            ];
                        }
                    );

                    $company = Company::query()->where('customer_number', $customerNumber)->first();

                    if (! $company) {
                        Log::notice(
                            sprintf(
                                '%s: Found invoice files for non-existent customer %s',
                                __CLASS__,
                                $customerNumber
                            )
                        );
                    }

                    return $files;
                }
            );
    }
}
