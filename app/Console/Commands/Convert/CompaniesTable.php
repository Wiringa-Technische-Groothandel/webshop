<?php

namespace WTG\Console\Commands\Convert;

use WTG\Converters\CompanyTableConverter as TableConverter;

/**
 * Convert companies table.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CompaniesTable extends AbstractTableCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:table:companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert the old companies table to the new structure.';

    /**
     * OrdersTable constructor.
     *
     * @param  TableConverter  $converter
     */
    public function __construct(TableConverter $converter)
    {
        parent::__construct($converter);
    }
}
