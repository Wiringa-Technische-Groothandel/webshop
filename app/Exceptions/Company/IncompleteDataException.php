<?php

namespace WTG\Exceptions\Company;

/**
 * Incomplete data exception.
 *
 * @package     WTG\Exceptions
 * @subpackage  Company
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IncompleteDataException extends \Exception implements \Throwable
{
    /**
     * IncompleteDataException constructor.
     *
     * @param  array  $messages
     * @param  int  $code
     * @param  \Throwable|null  $previous
     */
    public function __construct(array $messages = [], int $code = 0, ?\Throwable $previous = null)
    {
        $message = join("\n", $messages);

        parent::__construct($message, $code, $previous);
    }
}