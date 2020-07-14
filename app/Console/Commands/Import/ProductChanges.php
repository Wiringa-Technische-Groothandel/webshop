<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use WTG\RestClient\Api\RestManagerInterface;
use WTG\RestClient\Model\Rest\ErrorResponse;
use WTG\RestClient\Model\Rest\GetChangedProducts\Request;
use WTG\RestClient\Model\Rest\GetChangedProducts\Response;

class ProductChanges extends Command
{
    /**
     * @var string
     */
    protected $name = 'import:product-changes';

    /**
     * @var string
     */
    protected $description = 'Update the changed products only';

    protected RestManagerInterface $restManager;

    /**
     * ProductChanges constructor.
     *
     * @param RestManagerInterface $restManager
     */
    public function __construct(RestManagerInterface $restManager)
    {
        parent::__construct();

        $this->restManager = $restManager;
    }

    /**
     * Run the command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var Response $response */
        $response = $this->restManager->handle(new Request());

        if ($response instanceof ErrorResponse) {
            $this->error($response->exception->getMessage());

            if ($response->exception instanceof ConnectException) {
                $msg = "Partial product import failed, updating change id to prevent API overload.";

                Log::warning($msg);
                $this->warn($msg);

                $this->call('import:latest-change-id');
            }

            return Command::FAILURE;
        }

        $this->call('import:latest-change-id');

        $query = DB::table('config')->where('key', 'last_product_change_number');

        $lastChangeNumber = $query->value('value');

        if ($lastChangeNumber > $response->changeNumberEnd) {
            $this->error("Returned change number lower than last change number. Aborting...");

            return Command::FAILURE;
        }

        if ($lastChangeNumber == $response->changeNumberEnd) {
            $this->info("No new changes");

            return Command::SUCCESS;
        }

        DB::transaction(
            function () use ($query, $response) {
                $query->update(
                    ['value' => $response->changeNumberEnd]
                );

                $response->data->each(
                    function (array $result) {
                        DB::table('staged_product_changes')->insert(
                            [
                                'erp_id'        => $result['erp_id'],
                                'action'        => $result['action'],
                                'change_number' => $result['change_number'],
                            ]
                        );
                    }
                );
            }
        );
    }
}
