<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Sys\Cleanup;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Throwable;
use WTG\Models\Company;

/**
 * Cleanup old soft-deleted companies.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Companies extends Command
{
    /**
     * @var string
     */
    protected $name = 'sys:cleanup:companies';

    /**
     * @var string
     */
    protected $description = 'Prune soft-deleted companies older than x hours (Default = 6 hour)';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $time = now()->subHours(
            (int)$this->option('hours')
        );

        Company::onlyTrashed()
            ->where('deleted_at', '<=', $time)
            ->each(
                function (Company $company) {
                    $this->output->writeln(sprintf('Deleting company "%s"', $company->getName()));

                    $company->forceDelete();
                }
            );
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                "hours",
                "H",
                InputOption::VALUE_REQUIRED,
                "Remove soft-deleted companies older than this",
                6
            ]
        ];
    }
}
