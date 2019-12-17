<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Convert;

use WTG\Converters\CustomerTableConverter as TableConverter;

/**
 * Convert the 'users' table to the 'customers' table.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CustomersTable extends AbstractTableCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:table:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert the old users table to the new customers table structure.';

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
