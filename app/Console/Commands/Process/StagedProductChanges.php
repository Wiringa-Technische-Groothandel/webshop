<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Process;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Throwable;
use WTG\Foundation\Api\ErpModelInterface;
use WTG\Import\Importer\SingleProductImporter;
use WTG\Managers\ImportManager;
use WTG\Managers\ProductManager;

class StagedProductChanges extends Command
{
    /**
     * @var string
     */
    protected $name = 'process:staged-product-changes';

    /**
     * @var string
     */
    protected $description = 'Process the staged product changes';

    protected ProductManager $productManager;
    protected ImportManager $importManager;

    /**
     * StagedProductChanges constructor.
     *
     * @param ProductManager $productManager
     * @param ImportManager $importManager
     */
    public function __construct(ProductManager $productManager, ImportManager $importManager)
    {
        parent::__construct();

        $this->productManager = $productManager;
        $this->importManager = $importManager;
    }

    /**
     * Run the command.
     *
     * @return int
     * @throws Throwable
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $collection = DB::table('staged_product_changes')->orderBy('change_number')->get();
            $collection->each(
                function ($change) {
                    if ($change->action === 'D') {
                        try {
                            $product = $this->productManager->find($change->erp_id, ErpModelInterface::FIELD_ERP_ID);

                            $this->productManager->delete($product->getId());
                        } catch (ModelNotFoundException $e) {
                            //
                        }
                    } elseif ($change->action === 'C' || $change->action === 'N') {
                        /** @var SingleProductImporter $importer */
                        $importer = app(SingleProductImporter::class);
                        $importer->setErpIp($change->erp_id);

                        $this->importManager->run($importer);
                    }

                    DB::table('staged_product_changes')->delete($change->id);
                }
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Command::SUCCESS;
    }
}
