<?php

declare(strict_types=1);

namespace WTG\Import;

use Illuminate\Log\LogManager;
use Throwable;
use WTG\Import\Api\ImporterInterface;

/**
 * Import manager.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImportManager
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * @var Throwable
     */
    protected Throwable $lastError;

    /**
     * ImportManager constructor.
     *
     * @param LogManager $logManager
     */
    public function __construct(LogManager $logManager)
    {
        $this->logManager = $logManager;
    }

    /**
     * Run an importer.
     *
     * @param ImporterInterface $importer
     * @return bool
     * @throws Throwable
     */
    public function run(ImporterInterface $importer): bool
    {
        try {
            $importer->import();
        } catch (Throwable $throwable) {
            $this->logManager->error($throwable);
            $this->lastError = $throwable;

            return false;
        }

        return true;
    }

    /**
     * Get the last importer error.
     *
     * @return Throwable
     */
    public function getLastError(): Throwable
    {
        return $this->lastError;
    }
}
