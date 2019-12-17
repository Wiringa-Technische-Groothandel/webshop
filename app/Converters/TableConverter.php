<?php

declare(strict_types=1);

namespace WTG\Converters;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Table converter interface.
 *
 * @package     WTG\Converters
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface TableConverter
{
    /**
     * Run the converter.
     *
     * @param string $filePath
     * @return bool
     */
    public function run(string $filePath): bool;

    /**
     * Check if the file exists.
     *
     * @return bool
     */
    public function fileExists(): bool;

    /**
     * Read file contents.
     *
     * @return void
     * @throws Exception
     */
    public function readFileContents();

    /**
     * Parse the file contents.
     *
     * @return mixed
     */
    public function parseFileContents();

    /**
     * Create a new model.
     *
     * @param array $data
     * @return Model|null
     */
    public function createModel(array $data): ?Model;
}
