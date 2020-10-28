<?php

declare(strict_types=1);

namespace WTG\Managers;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use WTG\DTO\InvoiceFile;
use WTG\Models\Company;

/**
 * Invoice import.
 *
 * @package     WTG\Console
 * @subpackage  Commands\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class InvoiceManager
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
        $this->files = Cache::get(self::CACHE_KEY, collect());
    }

    /**
     * Get the invoice collection for a customer.
     *
     * @param Company $company
     * @param bool $sort
     * @param int $sortOrder
     * @return Collection|InvoiceFile[]
     */
    public function getForCompany(
        Company $company,
        bool $sort = true,
        int $sortOrder = self::SORT_ORDER_DESC
    ): Collection {
        /** @var Collection $files */
//        $files = $this->files->get($company->getCustomerNumber(), collect());
        $files = $this->files->get('10276', collect());

        if ($sort) {
            $files = $files->sortBy(
                function (InvoiceFile $file) {
                    return $file->getDate()->timestamp;
                }
            );
        }

        if ($sortOrder === self::SORT_ORDER_DESC) {
            $files = $files->reverse();
        }

        return $files;
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

                return new InvoiceFile(
                    $filename,
                    $match['customer'],
                    $match['invoice'],
                    CarbonImmutable::createFromFormat('dmYHis', ($match['date'] . $match['time']))
                );
            }
        )
            ->filter()
            ->groupBy(
                function (InvoiceFile $item) {
                    return $item->getCustomerNumber();
                }
            )
            ->map(
                function (Collection $fileGroup, int $customerNumber) {
                    $files = $fileGroup->mapWithKeys(
                        function (InvoiceFile $file) {
                            return [
                                $file->getInvoiceNumber() => $file
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
