<?php

declare(strict_types=1);

namespace WTG\Foundation\Logging\Handlers;

use Exception;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;

/**
 * Database log handler.
 *
 * @package     WTG
 * @subpackage  LogHandlers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DatabaseHandler extends AbstractHandler implements HandlerInterface
{
    /**
     * @var bool
     */
    protected bool $failed = false;

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
            $isRecordInserted = DB::table('logs')
                ->insert(
                    [
                        'message'    => $record['message'] ?? "No message provided",
                        'context'    => json_encode($record['context']),
                        'level'      => $record['level'],
                        'level_name' => $record['level_name'],
                        'logged_at'  => $record['datetime'] ?? now(),
                        'extra'      => json_encode($record['extra']),
                    ]
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
