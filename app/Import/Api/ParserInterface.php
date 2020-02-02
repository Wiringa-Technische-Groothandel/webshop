<?php

declare(strict_types=1);

namespace WTG\Import\Api;

/**
 * Parser interface.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface ParserInterface
{
    /**
     * Run the parser.
     *
     * @return Iterable
     */
    public function parse(): iterable;
}
