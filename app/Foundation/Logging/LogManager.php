<?php

declare(strict_types=1);

namespace WTG\Foundation\Logging;

use DateTimeImmutable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager as LaravelLogManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use WTG\Foundation\Logging\Model\Log;

/**
 * Log manager.
 *
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class LogManager extends LaravelLogManager
{
    /**
     * LogManager constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Log a line in the database.
     *
     * @param string $message
     * @param int $level
     * @param array $context
     * @param array $extra
     * @param null|DateTimeImmutable $time
     * @return bool
     */
    public function databaseLog(
        string $message,
        int $level = Logger::INFO,
        array $context = [],
        array $extra = [],
        ?DateTimeImmutable $time = null
    ): bool {
        if ($time === null) {
            $time = now();
        }

        return DB::table('logs')
            ->insert(
                [
                    'message'    => $message,
                    'context'    => json_encode($context),
                    'level'      => $level,
                    'level_name' => Logger::getLevelName($level) ?? 'UNKNOWN',
                    'logged_at'  => $time,
                    'extra'      => json_encode($extra),
                ]
            );
    }

    /**
     * @return void
     */
    public function truncateLogTable(): void
    {
        DB::table('logs')->truncate();
    }

    /**
     * @return Collection
     */
    public function getSortedLogs(): Collection
    {
        return Log::query()
            ->orderBy('logged_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }
}
