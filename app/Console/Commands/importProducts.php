<?php

namespace App\Console\Commands;

use DB;
use File;
use Helper;
use Validator;
use App\Content;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Exceptions\InvalidColumnCountException;

class importProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the products file and import the new products';

    /**
     * This will hold the path to the products csv file.
     *
     * @var string
     */
    private $filePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->filePath = storage_path('import/products.csv');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (File::exists($this->filePath)) {
            ini_set('memory_limit', '1G');

            $validator = Validator::make(
                ['fileType' => finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->filePath)],
                ['fileType' => 'required|string:text/plain|string:text/csv']
            );

            if ($validator->fails()) {
                $this->error('Invalid CSV file!');
            } else {
                $startTime = microtime(true);
                $fh = fopen($this->filePath, 'r');
                $lineCount = count(file($this->filePath));
                $bar = $this->output->createProgressBar($lineCount);
                $products_with_related_products = [];

                $bar->setRedrawFrequency(100);
                $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s% %product%');

                DB::beginTransaction();

                try {
                    // Truncate the products table
                    (new Product())->newQuery()->delete();

                    $line = 1;

                    while (! feof($fh)) {
                        //$data = preg_replace('/;$/', '', fgets($fh));
                        $data = str_getcsv(fgets($fh), ';');
                        $columnCount = count($data);

                        if ($columnCount === 1) {
                            $this->info("Skipping empty line {$line}");
                        // Make sure column count is 30
                        } elseif ($columnCount === 30) {
                            $bar->setMessage($data[3], 'product');

                            DB::table('products')->insert([
                                'name'             => $data[0],
                                'number'           => $data[3],
                                'group'            => $data[4],
                                'altNumber'        => $data[5],
                                'stockCode'        => $data[7],
                                'registered_per'   => $data[8],
                                'packed_per'       => $data[9],
                                'price_per'        => $data[10],
                                'refactor'         => preg_replace("/\,/", '.', $data[12]),
                                'supplier'         => $data[13],
                                'ean'              => $data[14],
                                'image'            => $data[15],
                                'length'           => $data[17],
                                'price'            => $data[18],
                                'vat'              => $data[20],
                                'brand'            => $data[21],
                                'series'           => $data[22],
                                'type'             => $data[23],
                                'special_price'    => ($data[24] === '' ? '0.00' : preg_replace("/\,/", '.', $data[24])),
                                'action_type'      => $data[25],
                                'keywords'         => $data[26],
                                'related_products' => $data[27],
                                'catalog_group'    => $data[28],
                                'catalog_index'    => $data[29],
                            ]);

                            $line++;

                            // Save the products with related products in an array for verification
                            if ($data[27] != '') {
                                $products_with_related_products[$data[3]] = $data[27];
                            }

                            $bar->advance();
                        } else {
                            throw new InvalidColumnCountException($line, $columnCount, 30);
                        }
                    }
                    fclose($fh);

                    $bar->finish();

                    $this->line("\r\n");

                    Helper::checkRelatedProducts($products_with_related_products);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    $errorMessage = 'Er is een fout opgetreden, de database is niet aangepast: <br />'.$e->getMessage();

                    unlink($this->filePath);
                    $this->error($errorMessage);

                    Content::where('name', 'admin.product_import')->update([
                        'content'    => $errorMessage,
                        'updated_at' => Carbon::now('Europe/Amsterdam'),
                        'error'      => true,
                    ]);

                    \Mail::send('email.import_error_notice', ['error' => $errorMessage, 'type' => 'product'], function ($message) {
                        $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                        $message->to('gfw@wiringa.nl');

                        $message->subject('[WTG Webshop] Product import error');
                    });

                    return 2;
                }

                $endTime = round(microtime(true) - $startTime, 4);

                $this->info($line.' products were imported in '.$endTime.' seconds using '.number_format(memory_get_peak_usage() / 1000000, 2).' MBs of memory');

                Content::where('name', 'admin.product_import')->update([
                    'content'    => $line.' producten zijn geimporteerd. <br />Geheugen gebruik: '.number_format(memory_get_peak_usage() / 1000000, 2).' MB',
                    'updated_at' => Carbon::now('Europe/Amsterdam'),
                    'error'      => false,
                ]);
            }

            // Always remove the file at the end of the import to make sure that it doesn't get parsed twice
            unlink($this->filePath);

            return 0;
        } else {
            $this->warn('No products file found.');

            return 1;
        }
    }
}
