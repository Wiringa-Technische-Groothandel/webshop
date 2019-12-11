<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Convert;

use WTG\Converters\FavoritesTableConverter as TableConverter;

/**
 * Convert the 'favorites' column to the 'favorites' table.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FavoritesTable extends AbstractTableCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:table:favorites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert the old favorites to the new favorites table structure.';

    /**
     * FavoritesTable constructor.
     *
     * @param  TableConverter  $converter
     */
    public function __construct(TableConverter $converter)
    {
        parent::__construct($converter);
    }
}
