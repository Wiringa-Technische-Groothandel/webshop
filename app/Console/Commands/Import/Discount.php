<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Throwable;
use WTG\Services\Import\Discounts as Service;

class Discount extends Command
{
    /**
     * @var string
     */
    protected $signature = 'import:discounts';

    /**
     * @var string
     */
    protected $description = 'Import discounts from storage/app/import/discount.';

    /**
     * @var Service
     */
    protected $service;

    /**
     * @var DatabaseManager
     */
    protected $dm;

    /**
     * AssortmentFiles constructor.
     *
     * @param Service $service
     * @param DatabaseManager $dm
     */
    public function __construct(Service $service, DatabaseManager $dm)
    {
        parent::__construct();

        $this->service = $service;
        $this->dm = $dm;
    }

    /**
     * Run the command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        $this->dm->transaction(
            function () {
                $this->service->run();
            }
        );
    }
}
