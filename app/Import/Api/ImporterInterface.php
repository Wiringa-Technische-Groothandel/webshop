<?php

declare(strict_types=1);

namespace WTG\Import\Api;

/**
 * Importer interface.
 *
 * @api
 * @package     WTG\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface ImporterInterface
{
    /**
     * Run the import.
     *
     * @return void
     */
    public function import(): void;
}
