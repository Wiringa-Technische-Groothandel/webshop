<?php

declare(strict_types=1);

namespace WTG\Import\Parser;

use Exception;
use WTG\Import\Api\ParserInterface;

/**
 * CSV with header parser.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CsvWithHeaderParser implements ParserInterface
{
    /**
     * @var string
     */
    protected string $filePath;

    /**
     * Run the importer.
     *
     * @return Iterable
     * @throws Exception
     */
    public function parse(): iterable
    {
        $f = fopen($this->filePath, 'r');
        $importCount = 0;

        $header = fgetcsv($f);

        while ($csvData = fgetcsv($f)) {
            $data = array_combine($header, $csvData);

            if (! $data) {
                throw new Exception('Failed to combine file header with csv data');
            }

            yield $data;

            $importCount++;
        }

        fclose($f);
    }

    /**
     * Set the file path to be parsed.
     *
     * @param string $filePath
     * @return void
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }
}
