<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use WTG\RestClient\Api\RestManagerInterface;
use WTG\RestClient\Model\Rest\ErrorResponse;
use WTG\RestClient\Model\Rest\GetLastChangeId\Request;
use WTG\RestClient\Model\Rest\GetLastChangeId\Response;

class LastestChangeId extends Command
{
    /**
     * @var string
     */
    protected $name = 'import:latest-change-id';

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

            return Command::FAILURE;
        }

        $query = DB::table('config')->where('key', 'last_product_change_number');

        $lastChangeNumber = $query->value('value');

        if ($lastChangeNumber > $response->changeNumberEnd) {
            if (! $this->ask('Returned change number lower than last change number, continue anyway?')) {
                $this->error("Aborting...");

                return Command::FAILURE;
            }
        }

        $query->update(
            ['value' => $response->changeNumberEnd]
        );

        $this->info("Updated change id to {$response->changeNumberEnd}");

        return Command::SUCCESS;
    }
}
