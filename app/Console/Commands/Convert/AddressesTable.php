<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Convert;

use WTG\Converters\AddressTableConverter as TableConverter;

/**
 * Convert the 'addresses' table to the 'addresses' table.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressesTable extends AbstractTableCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:table:addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert the old addresses table to the new addresses table structure.';

    /**
     * OrdersTable constructor.
     *
     * @param TableConverter $converter
     */
    public function __construct(TableConverter $converter)
    {
        parent::__construct($converter);
    }
}
