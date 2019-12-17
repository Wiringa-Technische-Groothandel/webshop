<?php

declare(strict_types=1);

namespace WTG\Import\Api;

/**
 * Processor interface.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface ProcessorInterface
{
    /**
     * Process the incoming data.
     *
     * @param array $data
     * @return void
     */
    public function process(array $data): void;
}
