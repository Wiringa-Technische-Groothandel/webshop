<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Illuminate\Support\Collection;
use Throwable;
use WTG\Import\Api\BulkDownloaderInterface;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\Api\ParserInterface;
use WTG\Import\Api\ProcessorInterface;

/**
 * Abstract multi processor model.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class MultiImporter implements ImporterInterface
{
    /**
     * Full path to the CSV file.
     *
     * @var string
     */
    public string $csvFileName = '';

    /**
     * @var BulkDownloaderInterface
     */
    protected BulkDownloaderInterface $downloader;

    /**
     * @var ProcessorInterface
     */
    protected ProcessorInterface $processor;

    /**
     * @var ParserInterface
     */
    protected ParserInterface $parser;

    /**
     * MultiImporter constructor.
     *
     * @param BulkDownloaderInterface $downloader
     * @param ProcessorInterface $processor
     * @param ParserInterface $parser
     */
    public function __construct(
        BulkDownloaderInterface $downloader,
        ProcessorInterface $processor,
        ParserInterface $parser
    ) {
        $this->downloader = $downloader;
        $this->processor = $processor;
        $this->parser = $parser;
    }

    /**
     * Create a CSV file with product data.
     *
     * @param string $filePath
     * @return void
     */
    protected function createCSV(string $filePath): void
    {
        $f = fopen($filePath, 'a+');
        $header = [];

        foreach ($this->downloader->download() as $itemCollection) {
            /** @var Collection $itemCollection */
            $itemCollection->each(
                function ($item) use (&$header, $f) {
                    $allAttributes = get_class_vars(get_class($item));
                    $attributes = array_merge($allAttributes, get_object_vars($item));

                    if (! $header) {
                        $header = array_keys($attributes);

                        fputcsv($f, $header);
                    }

                    fputcsv($f, $attributes);
                }
            );
        }

        fclose($f);
    }

    /**
     * Import the CSV.
     *
     * @param string $filePath
     * @return void
     * @throws Throwable
     */
    protected function importCSV(string $filePath): void
    {
        $this->parser->setFilePath($filePath);

        foreach ($this->parser->parse() as $line) {
            $this->processor->process($line);
        }
    }
}
