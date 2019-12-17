<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Convert;

use WTG\Converters\OrderTableConverter as TableConverter;

/**
 * Convert orders table.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OrdersTable extends AbstractTableCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:table:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert the old orders table to the new structure.';

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
