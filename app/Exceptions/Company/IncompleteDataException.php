<?php

declare(strict_types=1);

namespace WTG\Exceptions\Company;

use Exception;
use Throwable;

/**
 * Incomplete data exception.
 *
 * @package     WTG\Exceptions
 * @subpackage  Company
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IncompleteDataException extends Exception implements Throwable
{
    /**
     * @var array
     */
    private $errors;

    /**
     * IncompleteDataException constructor.
     *
     * @param  array  $errors
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(array $errors = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Data validation failed: incomplete data', $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}