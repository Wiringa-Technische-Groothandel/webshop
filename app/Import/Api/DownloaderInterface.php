<?php

declare(strict_types=1);

namespace WTG\Import\Api;

use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * Downloader interface.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface DownloaderInterface
{
    /**
     * @return ResponseInterface
     */
    public function download(): ResponseInterface;
}
