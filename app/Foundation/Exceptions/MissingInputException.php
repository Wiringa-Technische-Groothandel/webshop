<?php

declare(strict_types=1);

namespace WTG\Foundation\Exceptions;

use Throwable;

class MissingInputException extends InputException
{
    private const TEMPLATE = "Missing required input field [%s]";

    public function __construct(string $field, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::TEMPLATE, $field), $code, $previous);
    }
}
