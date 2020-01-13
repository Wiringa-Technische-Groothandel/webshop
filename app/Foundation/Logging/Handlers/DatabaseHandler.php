<?php

declare(strict_types=1);

namespace WTG\Foundation\Logging\Handlers;

use Exception;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use WTG\Foundation\Logging\LogManager;

/**
 * Database log handler.
 *
 * @package     WTG\Foundation
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DatabaseHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * @var bool
     */
    protected bool $failed = false;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * DatabaseHandler constructor.
     *
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->logManager = app(LogManager::class);
    }

    /**
     * @param array $record
     * @return bool
     */
    public function handle(array $record): bool
    {
        if ($this->failed) {
            return false;
        }

        $this->failed = true;

        try {
            $isRecordInserted = $this->logManager->databaseLog(
                $record['message'] ?? "No message provided",
                $record['level'],
                $record['context'],
                $record['extra'],
                $record['datetime'] ?? null
            );

            if (! $isRecordInserted) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        $this->failed = false;

        return false;
    }
}
