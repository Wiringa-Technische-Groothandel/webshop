<?php

namespace WTG\Services;

use Illuminate\Database\DatabaseManager;

/**
 * Import service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImportService
{
    /**
     * @var DatabaseManager
     */
    protected $dm;

    /**
     * @var Import\Assortment
     */
    protected $assortmentImporter;

    /**
     * ImportService constructor.
     *
     * @param  DatabaseManager  $dm
     * @param  Import\Assortment  $assortmentImporter
     */
    public function __construct(
        DatabaseManager $dm,
        Import\Assortment $assortmentImporter
    ) {
        $this->dm = $dm;
        $this->assortmentImporter = $assortmentImporter;
    }


    /**
     * Assortment import.
     *
     * @return void
     * @throws \Throwable
     */
    public function assortment()
    {
        $this->dm->transaction(function () {
            $this->assortmentImporter->run();
        });
    }
}