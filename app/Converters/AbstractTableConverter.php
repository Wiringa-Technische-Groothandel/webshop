<?php

namespace WTG\Converters;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract table converter.
 *
 * @package     WTG\Converters
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class AbstractTableConverter implements TableConverter
{
    /**
     * @var Collection
     */
    protected $fileContents;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var array
     */
    protected $csvFields = [];

    /**
     * Run the converter
     *
     * @param  string  $file
     * @return bool
     */
    public function run(string $file): bool
    {
        ini_set('memory_limit', '-1');

        $this->filePath = $file;

        if (!$this->fileExists()) {
            return false;
        }

        $this->readFileContents();

        \DB::transaction(function () {
            $this->parseFileContents();
        });

        return true;
    }

    /**
     * Check if the file exists.
     *
     * @return bool
     */
    public function fileExists(): bool
    {
        return File::exists($this->filePath);
    }

    /**
     * Read file contents.
     *
     * @return void
     * @throws \Exception
     */
    public function readFileContents()
    {
        $lines = [];

        $file = fopen($this->filePath, "r");

        while (($data = fgetcsv($file, 1)) !== FALSE) {
            $lines[] = $data;
        }
        fclose($file);

        $this->fileContents = collect($lines);
    }

    /**
     * Parse the file contents.
     *
     * @return void
     * @throws \Exception
     */
    public function parseFileContents()
    {
        $this->fileContents->each(function ($item) {
            $model = $this->createModel($this->mapCsvFields($item));

            if ($model) {
                $model->save();
            }
        });
    }

    /**
     * Create a new entity.
     *
     * @param  array  $data
     * @return Model|null
     */
    public abstract function createModel(array $data): ?Model;

    /**
     * Map the csv fields.
     *
     * @param  array  $data
     * @return array
     */
    public function mapCsvFields(array $data)
    {
        return array_combine($this->csvFields, $data);
    }
}