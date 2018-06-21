<?php

namespace WTG\Services\Import;

use Carbon\Carbon;
use WTG\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Invoice import.
 *
 * @package     WTG\Console
 * @subpackage  Commands\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Invoices
{
    const FILENAME_PATTERN = '/Verzamelfactuur_(?P<invoice>[0-9]{8})_(?P<customer>[0-9]{5})\.PDF$/';
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
     * @param  int  $customerNumber
     * @return \Illuminate\Support\Collection
     */
    public function getForCustomer(int $customerNumber): Collection
    {
        $files = $this->getFileList();

        return $files->get($customerNumber, collect());
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

        $this->files = \Cache::remember('invoice_files', 60 * 12, function () {
            return collect(
                $this->fs->disk('sftp')->allFiles('invoices')
            )->map(function ($filename) {
                if (! preg_match(self::FILENAME_PATTERN, $filename, $match)) {
                    // Remove the files that should not be in the folder
                    $this->fs->delete($filename);

                    return null;
                }

                return collect([
                    'filename' => $filename,
                    'customer' => (int) $match['customer'],
                    'invoice'  => (int) $match['invoice']
                ]);
            })
            ->filter()
            ->groupBy(function (Collection $item) {
                return $item->get('customer');
            })
            ->map(function (Collection $fileGroup, int $customerNumber) {
                $files = $fileGroup->map(function (Collection $file) {
                    return $file->get('filename');
                });

                $invoices = $fileGroup->map(function (Collection $file) {
                    return $file->get('invoice');
                })->sort()->reverse();

                $company = Company::where('customer_number', $customerNumber)->first();

                if (! $company) {
                    \Log::notice('Found invoice files for non-existant customer ' . $customerNumber);
                }

                return collect([
                    'company' => $company,
                    'files' => $files,
                    'invoices' => $invoices,
                    'count' => $files->count()
                ]);
            });
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
}