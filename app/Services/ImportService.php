<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Database\DatabaseManager;
use Throwable;

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
     * @var Import\Discounts
     */
    protected $discountImporter;

    /**
     * ImportService constructor.
     *
     * @param DatabaseManager $dm
     * @param Import\Assortment $assortmentImporter
     * @param Import\Discounts $discountImporter
     */
    public function __construct(
        DatabaseManager $dm,
        Import\Assortment $assortmentImporter,
        Import\Discounts $discountImporter
    ) {
        $this->dm = $dm;
        $this->assortmentImporter = $assortmentImporter;
        $this->discountImporter = $discountImporter;
    }


    /**
     * Assortment import.
     *
     * @return void
     * @throws Throwable
     */
    public function assortment(): void
    {
        $this->dm->transaction(
            function () {
                $this->assortmentImporter->run();
            }
        );
    }

    /**
     * Discount import.
     *
     * @return void
     * @throws Throwable
     */
    public function discounts(): void
    {
        $this->dm->transaction(
            function () {
                $this->discountImporter->run();
            }
        );
    }
}
