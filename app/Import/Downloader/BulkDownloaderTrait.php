<?php

declare(strict_types=1);

namespace WTG\Import\Downloader;

/**
 * Bulk downloader trait.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait BulkDownloaderTrait
{
    /**
     * @var int
     */
    protected int $offset = 0;

    /**
     * @var int
     */
    protected int $limit = 200;

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }
}
