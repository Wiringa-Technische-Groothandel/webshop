<?php

declare(strict_types=1);

namespace WTG\Import\Api;

/**
 * Downloader interface.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface BulkDownloaderInterface
{
    /**
     * @return Iterable
     */
    public function download(): iterable;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void;
}
