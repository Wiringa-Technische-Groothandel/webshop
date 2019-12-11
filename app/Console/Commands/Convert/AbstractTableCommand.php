<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Convert;

use Illuminate\Console\Command;
use WTG\Converters\TableConverter;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

/**
 * Abstract table command.
 *
 * @package     WTG\Console
 * @subpackage  Commands\Convert
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class AbstractTableCommand extends Command
{
    /**
     * @var TableConverter
     */
    protected $converter;

    /**
     * Create a new command instance.
     *
     * @param  TableConverter  $converter
     */
    public function __construct(TableConverter $converter)
    {
        parent::__construct();

        $this->converter = $converter;

        $this->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Path to file.');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->option('file');

        if (!$file) {
            $this->output->error("No file name given.");

            return 1;
        }

        if (! File::exists($file)) {
            $this->output->error("File '$file' not found.");

            return 1;
        }

        $success = $this->converter->run($file);

        if ($success) {
            $this->output->success("Conversion completed");

            return 0;
        } else {
            $this->output->error("Conversion failed");

            return 1;
        }
    }
}